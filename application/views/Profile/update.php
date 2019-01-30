<?php

$profileImage = ($user->profile_pic) ? base_url('public/uploads/'.$user->profile_pic): base_url('public/img/default_profile_pic.jpg');

?>
<!-- Upload Profile Pic Modalsss-->
<div id="change-profile-pic-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-picture-o"></i> Upload Profile Picture</h4>
            </div>
            <div class="modal-body text-center">
                <div class="media">
                    <img src="<?php echo $profileImage; ?>" class="img-responsive img-thumbnail" id="profile-pic-preview" alt="">
                </div>
                <br>
                <form action="<?php echo base_url('profile/upload_profile_pic')?>" id="upload-profile-pic-form" method="POST"  enctype="multipart/form-data">
                    <input type="file" class="form-control" name="profile_pic" id="select_profile_pic">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-left" data-form="#upload-profile-pic-form">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-2" id="profile-page-sidebar">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <div class="media">
                        <img src="<?php echo $profileImage; ?>" class="img-responsive img-thumbnail" alt="">
                        <a href="#" data-toggle="modal" data-target="#change-profile-pic-modal" id="edit_profile_pic" title="Change Profile Picture">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <h3 class="text-primary"><?php echo $user->first_name,' ',$user->last_name ; ?></h3>
                    <hr>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <li class="list-group-item"><i class="fa fa-user"></i> Profile</li>
                    <li class="list-group-item active"><i class="fa fa-pencil"></i> Update Profile</li>
                    <li class="list-group-item"><i class="fa fa-cogs"></i> Settings</li>
                    <li class="list-group-item"><i class="fa fa-bell"></i> Notifications</li>
                    <li class="list-group-item"><i class="fa fa-sign-out"></i> Logout</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <h1 class="page-header">Update Profile</h1>
        <div class="row">
            <div class="col-md-8">
                <form action="<?php echo base_url('Profile/save_profile')?>" id="update-profile-form" method="POST" class="validateForm">
                    <fieldset>
                        <legend>Update Your Profile Information</legend>
                        <?php echo $this->session->flashdata('msg'); ?>
                        <?php $this->load->view('partials/_form', ['formField' => $updateProfileFields])?>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </fieldset>
                </form>
            </div>

            <div class="col-md-3">
                <form action="<?php echo base_url('Profile/save_specializations')?>" id="forgot-password-form" method="POST" class="validateForm">
                    <fieldset>
                        <legend>Specialty Areas</legend>
                        <div class="alert alert-info">Please select at most 5 categories. You will Receive alerts for these categories</div>
                        <label for="specializations">Specialty Areas</label>
                        <select name="specializations[]" id="specializations" multiple class="form-control multi-select">
                            <?php foreach ($specializationOptions as $key => $option): ?>
                                <option value="<?php echo $key; ?>" <?php echo in_array($option,$userSpecialization) ? 'selected' : ''?> ><?php echo $option; ?></option>
                            <?php endforeach;?>
                        </select>
                        <br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

</div>

<div class="row" id="footer">
    <div class="col-md-12">
        <span class="pull-right">&copy; Copyright 2016 </span>
    </div>
</div>