<?php
$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$settings=SwpmSettings::get_instance();
$force_strong_pass=$settings->get_value('force-strong-passwords');
if (!empty($force_strong_pass)) {
    $pass_class="validate[custom[strongPass],minSize[8]]";
} else {
    $pass_class="";
}
SimpleWpMembership::enqueue_validation_scripts();
//The admin ajax causes an issue with the JS validation if done on form submission. The edit profile doesn't need JS validation on email. There is PHP validation which will catch any email error.
//SimpleWpMembership::enqueue_validation_scripts(array('ajaxEmailCall' => array('extraData'=>'&action=swpm_validate_email&member_id='.SwpmAuth::get_instance()->get('member_id'))));
?>

<div class="swpm-edit-profile-form">
    <form id="swpm-editprofile-form" name="swpm-editprofile-form" method="post" action="" class="swpm-validate-form">
        <?php wp_nonce_field('swpm_profile_edit_nonce_action', 'swpm_profile_edit_nonce_val') ?>
        <table>
            <?php apply_filters('swpm_edit_profile_form_before_username', ''); ?>
            <tr class="swpm-profile-username-row" <?php apply_filters('swpm_edit_profile_form_username_tr_attributes', ''); ?>>
                <td><label for="user_name"><?php echo SwpmUtils::_('Username'); ?></label></td>
                <td><?php echo $user_name ?></td>
            </tr>
            <tr class="swpm-profile-email-row">
                <td><label for="email"><?php echo SwpmUtils::_('Email'); ?></label></td>
                <td><input readonly type="text" id="email" name="email" size="50" autocomplete="off" class="form-control" value="<?php echo $email; ?>" /></td>
            </tr>
            <tr class="swpm-profile-password-row">
                <td><label for="password"><?php echo SwpmUtils::_('Password'); ?></label></td>
                <td><input type="password" id="password" value="" size="50" name="password" class="<?php echo $pass_class;?>" autocomplete="off" placeholder="<?php echo SwpmUtils::_('Leave empty to keep the current password'); ?>" /></td>
            </tr>
            <tr class="swpm-profile-password-retype-row">
                <td><label for="password_re"><?php echo SwpmUtils::_('Repeat Password'); ?></label></td>
                <td><input type="password" id="password_re" value="" size="50" name="password_re" autocomplete="off" placeholder="<?php echo SwpmUtils::_('Leave empty to keep the current password'); ?>" /></td>
            </tr>

            <?php apply_filters('swpm_edit_profile_form_after_membership_level', ''); ?>
        </table>
        <?php apply_filters('swpm_edit_profile_form_before_submit', ''); ?>
        <p class="swpm-edit-profile-submit-section">
            <input type="submit" value="<?php echo SwpmUtils::_('Update') ?>" class="swpm-edit-profile-submit btn btn-primary" name="swpm_editprofile_submit" />
        </p>
        <?php echo SwpmUtils::delete_account_button(); ?>

        <input type="hidden" name="action" value="custom_posts" />

    </form>
</div>