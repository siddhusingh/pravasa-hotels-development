<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>First Follow-Up Reminder</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f6fa; padding:20px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; padding:20px; border:1px solid #ddd;">
                    <tr>
                        <td style="font-size:16px; color:#333;">

                            <p>Hi <strong><?php echo $lead->agent_name; ?></strong>,</p>

                            <p>
                                This is a gentle reminder for the first follow-up regarding the below lead assigned to you.
                                As per your selected follow-up schedule (<?php echo $lead->followup_date; ?>),
                                please connect with the customer and update the lead status.
                            </p>

                            <h3 style="color:#2c3e50; border-bottom:2px solid #eee; padding-bottom:6px;">✅ Lead Details</h3>

                            <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td width="35%" style="font-weight:bold;">Lead ID:</td>
                                    <td><?php echo $lead->id; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Lead Name:</td>
                                    <td><?php echo $lead->user_name; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Contact Number:</td>
                                    <td><?php echo $lead->phone_number; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Email:</td>
                                    <td><?php echo $lead->email; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">City / Location:</td>
                                    <td><?php echo $lead->city_name; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Property / Hotel:</td>
                                    <td><?php echo $lead->hotel_name; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Department:</td>
                                    <td><?php echo $lead->department_name; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Lead Source:</td>
                                    <td><?php echo $lead->user_channel; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Current Status:</td>
                                    <td><?php echo $lead->status; ?></td>
                                </tr>


                                <?php
                                // Format booking_enquiry_date or show NA
                                $booking_enquiry_date = !empty($lead->booking_enquiry_date)
                                    ? date('d-m-Y', strtotime($lead->booking_enquiry_date))
                                    : 'NA';

                                // Format created_at or show NA
                                $created_at = !empty($lead->created_at)
                                    ? date('d-m-Y', strtotime($lead->created_at))
                                    : 'NA';
                                ?>

                                <tr>
                                    <td style="font-weight:bold;">Booking Date:</td>
                                    <td><?php echo $booking_enquiry_date; ?></td>
                                </tr>

                                <tr>
                                    <td style="font-weight:bold;">Created at:</td>
                                    <td><?php echo $created_at; ?></td>
                                </tr>


                                <tr>
                                    <td style="font-weight:bold;">Last Remark:</td>
                                    <td><?php echo $lead->remark; ?></td>
                                </tr>


                            </table>

                            <br>

                            <p>
                                Kindly check with the customer and update the lead status to ensure smooth and timely follow-up.
                            </p>

                            <p>
                                <strong>Please update the lead by:</strong>
                                <strong><?php echo $lead->followup_one_date; ?></strong>
                            </p>

                            <br>

                            <p>
                                Thanks,<br>
                                <strong><?php echo $lead->your_name; ?></strong><br>
                                <?php echo $lead->company_name; ?>
                            </p>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>