
<tr>
  <th>
    <label for="cornerstone-fields-allowed_post_types-1">
      <strong><?php _e( 'Allowed Post Types', csl18n() ); ?></strong>
      <span><?php _e( 'Select which post types to enable for Cornerstone.', csl18n() ); ?></span>
    </label>
  </th>
  <td>
    <fieldset>
     <?php echo $this->settings->renderField( 'allowed_post_types', array( 'type' => 'checkbox', 'value' => $this->settings->getPostTypes(), 'default' => array('page', 'post') ) ) ?>
    </fieldset>
  </td>
</tr>

<tr>
  <th>
    <label for="cornerstone-fields-permitted_roles-1">
      <strong><?php _e( 'Permissions', csl18n() ); ?></strong>
      <span><?php _e( 'Enable cornerstone for specific roles.', csl18n() ); ?></span>
    </label>
  </th>
  <td>
    <fieldset>
      <div class="cs_setting_checkbox"><label><input type="checkbox" disabled="disabled" class="checkbox"  value="administrator" checked="checked"> Administrator</label></div>
      <?php echo $this->settings->renderField( 'permitted_roles', array( 'type' => 'checkbox', 'value' => $this->settings->getRoles(), 'default' => array('administrator', 'editor', 'author') ) ) ?>
    </fieldset>
  </td>
</tr>