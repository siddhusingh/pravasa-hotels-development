<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RestaurantBookingModel extends CI_Model
{
    private $blockingStatuses = ['Reserved', 'Seated'];

    public function normalizeTableIds($tableIds)
    {
        if (!is_array($tableIds)) {
            $tableIds = ($tableIds === null || $tableIds === '') ? [] : explode(',', (string) $tableIds);
        }

        $normalized = [];
        foreach ($tableIds as $tableId) {
            $tableId = (int) trim((string) $tableId);
            if ($tableId > 0) {
                $normalized[$tableId] = $tableId;
            }
        }

        return array_values($normalized);
    }

    public function validateSelection($bookingDate, $restaurantId, $categoryId, $tableIds, $slotTypeId, $timeSlotId = null, $allowPastDate = false)
    {
        $errors = [];
        $restaurantId = (int) $restaurantId;
        $categoryId = (int) $categoryId;
        $slotTypeId = (int) $slotTypeId;
        $timeSlotId = $timeSlotId === null ? null : (int) $timeSlotId;
        $tableIds = $this->normalizeTableIds($tableIds);

        $parsedDate = DateTime::createFromFormat('!Y-m-d', (string) $bookingDate);
        if (!$parsedDate || $parsedDate->format('Y-m-d') !== $bookingDate) {
            $errors['booking_date'] = 'Please select a valid booking date.';
        } elseif (!$allowPastDate && $bookingDate < date('Y-m-d')) {
            $errors['booking_date'] = 'Booking date cannot be in the past.';
        }

        if ($restaurantId <= 0) {
            $errors['restaurant_id'] = 'Please select a restaurant.';
        }
        if ($categoryId <= 0) {
            $errors['table_category_id'] = 'Please select a table category.';
        }
        if (empty($tableIds)) {
            $errors['table_id'] = 'Please select at least one table.';
        }
        if ($slotTypeId <= 0) {
            $errors['slot_type_id'] = 'Please select a slot type.';
        }
        if ($timeSlotId !== null && $timeSlotId <= 0) {
            $errors['time_slot_id'] = 'Please select a time slot.';
        }

        if (!empty($errors)) {
            return $errors;
        }

        $category = $this->db
            ->select('id')
            ->from('table_categories')
            ->where('id', $categoryId)
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'active')
            ->where('is_deleted', 0)
            ->get()
            ->row();

        if (!$category) {
            $errors['table_category_id'] = 'The selected table category is unavailable for this restaurant.';
        }

        $validTables = $this->db
            ->select('id')
            ->from('tables')
            ->where('restaurant_id', $restaurantId)
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->where('is_deleted', 0)
            ->where_in('id', $tableIds)
            ->get()
            ->result();

        if (count($validTables) !== count($tableIds)) {
            $errors['table_id'] = 'One or more selected tables are unavailable for this restaurant and category.';
        }

        if ($timeSlotId !== null) {
            $slot = $this->db
                ->select('id')
                ->from('time_slots')
                ->where('id', $timeSlotId)
                ->where('slot_type_id', $slotTypeId)
                ->where('status', 'active')
                ->where('is_deleted', 0)
                ->get()
                ->row();

            if (!$slot) {
                $errors['time_slot_id'] = 'The selected time slot is unavailable for this slot type.';
            }
        }

        return $errors;
    }

    public function getAvailability($bookingDate, $restaurantId, $tableIds, $slotTypeId, $excludeLeadId = null)
    {
        $restaurantId = (int) $restaurantId;
        $slotTypeId = (int) $slotTypeId;
        $excludeLeadId = $excludeLeadId === null ? null : (int) $excludeLeadId;
        $tableIds = $this->normalizeTableIds($tableIds);

        $slots = $this->db
            ->select('id, slot_type_id, slot_name, start_time, end_time')
            ->from('time_slots')
            ->where('slot_type_id', $slotTypeId)
            ->where('status', 'active')
            ->where('is_deleted', 0)
            ->order_by('start_time', 'ASC')
            ->get()
            ->result_array();

        if (empty($slots) || empty($tableIds)) {
            return $this->decorateAvailability($slots, []);
        }

        $this->db
            ->select('lrt.table_id, l.id AS lead_id, l.time_slot_id, ts.start_time, ts.end_time, t.table_name, t.table_number')
            ->from('lead_reserved_tables lrt')
            ->join('leads l', 'l.id = lrt.lead_id', 'inner')
            ->join('time_slots ts', 'ts.id = l.time_slot_id', 'inner')
            ->join('tables t', 't.id = lrt.table_id', 'inner')
            ->where('l.is_deleted', 0)
            ->where('l.booking_date', $bookingDate)
            ->where('l.restaurant_id', $restaurantId)
            ->where_in('l.table_reservation_status', $this->blockingStatuses)
            ->where_in('lrt.table_id', $tableIds);

        if ($excludeLeadId !== null && $excludeLeadId > 0) {
            $this->db->where('l.id !=', $excludeLeadId);
        }

        $conflicts = $this->db->get()->result_array();

        // Until the Hotel Admin and Agent flows adopt the normalized table,
        // include their legacy single-table bookings in Super Admin availability.
        $this->db
            ->select('l.table_id, l.id AS lead_id, l.time_slot_id, ts.start_time, ts.end_time, t.table_name, t.table_number')
            ->from('leads l')
            ->join('time_slots ts', 'ts.id = l.time_slot_id', 'inner')
            ->join('tables t', 't.id = l.table_id', 'inner')
            ->where('l.is_deleted', 0)
            ->where('l.booking_date', $bookingDate)
            ->where('l.restaurant_id', $restaurantId)
            ->where_in('l.table_reservation_status', $this->blockingStatuses)
            ->where_in('l.table_id', $tableIds)
            ->where('NOT EXISTS (SELECT 1 FROM lead_reserved_tables legacy_lrt WHERE legacy_lrt.lead_id = l.id)', null, false);

        if ($excludeLeadId !== null && $excludeLeadId > 0) {
            $this->db->where('l.id !=', $excludeLeadId);
        }

        $legacyConflicts = $this->db->get()->result_array();
        if (!empty($legacyConflicts)) {
            $conflicts = array_merge($conflicts, $legacyConflicts);
        }

        return $this->decorateAvailability($slots, $conflicts);
    }

    public function findConflict($bookingDate, $restaurantId, $tableIds, $slotTypeId, $timeSlotId, $excludeLeadId = null)
    {
        $availability = $this->getAvailability(
            $bookingDate,
            $restaurantId,
            $tableIds,
            $slotTypeId,
            $excludeLeadId
        );

        foreach ($availability as $slot) {
            if ((int) $slot['id'] === (int) $timeSlotId) {
                return !empty($slot['available']) ? null : $slot;
            }
        }

        return [
            'id' => (int) $timeSlotId,
            'available' => false,
            'conflicting_tables' => [],
            'reason' => 'The selected time slot is unavailable.'
        ];
    }

    public function lockTables($tableIds)
    {
        $tableIds = $this->normalizeTableIds($tableIds);
        if (empty($tableIds)) {
            return;
        }

        sort($tableIds, SORT_NUMERIC);
        $escapedIds = implode(',', array_map('intval', $tableIds));
        $this->db->query("SELECT id FROM tables WHERE id IN ($escapedIds) ORDER BY id FOR UPDATE");
    }

    public function replaceLeadTables($leadId, $tableIds)
    {
        $leadId = (int) $leadId;
        $tableIds = $this->normalizeTableIds($tableIds);

        $this->db->where('lead_id', $leadId)->delete('lead_reserved_tables');

        foreach ($tableIds as $tableId) {
            if (!$this->db->insert('lead_reserved_tables', [
                'lead_id' => $leadId,
                'table_id' => $tableId
            ])) {
                return false;
            }
        }

        return true;
    }

    public function getLeadTableIds($leadId)
    {
        $rows = $this->db
            ->select('table_id')
            ->from('lead_reserved_tables')
            ->where('lead_id', (int) $leadId)
            ->order_by('table_id', 'ASC')
            ->get()
            ->result_array();

        return array_map('intval', array_column($rows, 'table_id'));
    }

    private function decorateAvailability($slots, $conflicts)
    {
        foreach ($slots as &$slot) {
            $conflictingTables = [];

            foreach ($conflicts as $conflict) {
                if ($this->timesOverlap(
                    $slot['start_time'],
                    $slot['end_time'],
                    $conflict['start_time'],
                    $conflict['end_time']
                )) {
                    $label = trim((string) $conflict['table_name']);
                    if ($label === '') {
                        $label = 'Table ' . $conflict['table_number'];
                    }
                    $conflictingTables[(int) $conflict['table_id']] = $label;
                }
            }

            $slot['conflicting_tables'] = array_values($conflictingTables);
            $slot['available'] = empty($conflictingTables);
            $slot['reason'] = $slot['available']
                ? ''
                : 'Already booked: ' . implode(', ', $slot['conflicting_tables']);
        }
        unset($slot);

        return $slots;
    }

    private function timesOverlap($candidateStart, $candidateEnd, $bookedStart, $bookedEnd)
    {
        $candidateStart = $this->timeToSeconds($candidateStart);
        $candidateEnd = $this->timeToSeconds($candidateEnd);
        $bookedStart = $this->timeToSeconds($bookedStart);
        $bookedEnd = $this->timeToSeconds($bookedEnd);

        if ($candidateEnd <= $candidateStart) {
            $candidateEnd += 86400;
        }
        if ($bookedEnd <= $bookedStart) {
            $bookedEnd += 86400;
        }

        return $candidateStart < $bookedEnd && $candidateEnd > $bookedStart;
    }

    private function timeToSeconds($time)
    {
        $parts = array_map('intval', explode(':', (string) $time));
        return (($parts[0] ?? 0) * 3600) + (($parts[1] ?? 0) * 60) + ($parts[2] ?? 0);
    }
}
