  <table id="complex_header" class="text-fade table table-bordered display" style="width:100%">
      <thead>
          <tr class="text-dark">
              <th>Sr. No.</th>

              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Status</th>


              <th>Created on</th>
              <th>Updated on</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
          <?php if (!empty($admins)) {
                $number = 1;
                foreach ($admins as $each) {
                    $encrypted_id = encrypt_id($each->id);
                    ?>
                  <tr>
                      <td><?php echo $number++; ?></td>
                      <td><?= $each->full_name ?></td>
                      <td><?= $each->email ?></td>
                      <td><?= $each->phone ?></td>
                      <td><span class="badge bg-<?= $each->status == 'active' ? 'success' : 'danger' ?>"><?= $each->status ?></span></td>

                      <td><?= $each->created_at ?></td>
                      <td><?= $each->updated_at ?></td>
                      <td class="table-action min-w-100">

                          <a href="javascript:void(0)" class="text-fade hover-primary edit-admin"
                              data-record_id="<?= $encrypted_id ?>">
                              <i class="fa fa-edit"></i> Edit
                          </a>
                          <a href="javascript:void(0)" class="text-fade hover-primary delete-admin"
                              data-record_id="<?= $encrypted_id ?>">
                              <i class="fa fa-trash"></i> Delete
                          </a>
                      </td>
                  </tr>
          <?php }
            } ?>
      </tbody>

  </table>
