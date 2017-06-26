<?php

/**
 * This file contains functions that return data structures of all the Core field types info - settings, validation
 * etc, for use by the module's installation and upgrade functions.
 */


// ------------------------------------------------------------------------------------------------

function cft_get_field_types()
{
    $cft_field_types = array();

    $textbox_css =<<< END
input.cf_size_tiny {
  width: 30px;
}
input.cf_size_small {
  width: 80px;
}
input.cf_size_medium {
  width: 150px;
}
input.cf_size_large {
  width: 250px;
}
input.cf_size_full_width {
  width: 99%;
}
END;

    $textbox_edit_field =<<< END
<input type="text" name="{\$NAME}" value="{\$VALUE|escape}" class="{\$size}{if \$highlight} {\$highlight}{/if}"
  {if \$maxlength}maxlength="{\$maxlength}"{/if} />
  {if \$comments}
    <div class="cf_field_comments">{\$comments}</div>
  {/if}
END;

    $cft_field_types["textbox"] = array(
        "field_type" => array(
            "is_editable"                    => "no",
            "non_editable_info"              => "'{\$LANG.text_non_deletable_fields}'",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.word_textbox}",
            "field_type_identifier"          => "textbox",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "textbox",
            "compatible_field_sizes"         => "1char,2chars,tiny,small,medium,large,very_large",
            "view_field_rendering_type"      => "smarty",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "",
            "view_field_smarty_markup"       => "{\$VALUE|htmlspecialchars}",
            "edit_field_smarty_markup"       => $textbox_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => $textbox_css,
            "resources_js"                   => ""
        ),

        "settings" => array(
            // Size
            array(
                "field_label"              => "{\$LANG.word_size}",
                "field_setting_identifier" => "size",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "cf_size_medium",
                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.word_tiny}",
                        "option_value"      => "cf_size_tiny",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_small}",
                        "option_value"      => "cf_size_small",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_medium}",
                        "option_value"      => "cf_size_medium",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_large}",
                        "option_value"      => "cf_size_large",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_full_width}",
                        "option_value"      => "cf_size_full_width",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Max Length
            array(
                "field_label"              => "{\$LANG.phrase_max_length}",
                "field_setting_identifier" => "maxlength",
                "field_type"               => "textbox",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            ),

            // Highlight
            array(
                "field_label"              => "{\$LANG.word_highlight}",
                "field_setting_identifier" => "highlight",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",

                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.word_none}",
                        "option_value"      => "",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_red}",
                        "option_value"      => "cf_colour_red",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_orange}",
                        "option_value"      => "cf_colour_orange",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_yellow}",
                        "option_value"      => "cf_colour_yellow",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_green}",
                        "option_value"      => "cf_colour_green",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_blue}",
                        "option_value"      => "cf_colour_blue",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "no",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            ),
            array(
                "rsv_rule"                 => "valid_email",
                "rule_label"               => "{\$LANG.phrase_valid_email}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "no",
                "default_error_message"    => "{\$LANG.validation_default_rule_valid_email}"
            ),
            array(
                "rsv_rule"                 => "digits_only",
                "rule_label"               => "{\$LANG.phrase_numbers_only}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "no",
                "default_error_message"    => "{\$LANG.validation_default_rule_numbers_only}"
            ),
            array(
                "rsv_rule"                 => "letters_only",
                "rule_label"               => "{\$LANG.phrase_letters_only}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "no",
                "default_error_message"    => "{\$LANG.validation_default_rule_letters_only}"
            ),
            array(
                "rsv_rule"                 => "is_alpha",
                "rule_label"               => "{\$LANG.phrase_alphanumeric}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "no",
                "default_error_message"    => "{\$LANG.validation_default_rule_alpha}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $textarea_view_field =<<< END
{if \$CONTEXTPAGE == "edit_submission"}  
  {\$VALUE|nl2br}
{else}
  {\$VALUE}
{/if}
END;

    $textarea_edit_field =<<< END
{* figure out all the classes *}
{assign var=classes value=\$height}
{if \$highlight_colour}
  {assign var=classes value="`\$classes` `\$highlight_colour`"}
{/if}
{if \$input_length == "words" && \$maxlength != ""}
  {assign var=classes value="`\$classes` cf_wordcounter max`\$maxlength`"}
{elseif \$input_length == "chars" && \$maxlength != ""}
  {assign var=classes value="`\$classes` cf_textcounter max`\$maxlength`"}
{/if}

<textarea name="{\$NAME}" id="{\$NAME}_id" class="{\$classes}">{\$VALUE}</textarea>

{if \$input_length == "words" && \$maxlength != ""}
  <div class="cf_counter" id="{\$NAME}_counter">
    {\$maxlength} {\$LANG.phrase_word_limit_p} <span></span> {\$LANG.phrase_remaining_words}
  </div>
{elseif \$input_length == "chars" && \$maxlength != ""}
  <div class="cf_counter" id="{\$NAME}_counter">
    {\$maxlength} {\$LANG.phrase_characters_limit_p} <span></span> {\$LANG.phrase_remaining_characters}
  </div>
{/if}

{if \$comments}
  <div class="cf_field_comments">{\$comments|nl2br}</div>
{/if}
END;

    $textarea_css =<<< END
.cf_counter span {
  font-weight: bold;
}
textarea {
  width: 99%;
}
textarea.cf_size_tiny {
  height: 30px;
}
textarea.cf_size_small {
  height: 80px;
}
textarea.cf_size_medium {
  height: 150px;
}
textarea.cf_size_large {
  height: 300px;
}
END;

    $textarea_js =<<< END
/**
 * The following code provides a simple text/word counter option for any 
 * textarea. It either just keeps counting up, or limits the results to a
 * certain number - all depending on what the user has selected via the
 * field type settings.
 */
var cf_counter = {};
cf_counter.get_max_count = function(el) {
  var classes = $(el).attr('class').split(" ").slice(-1);
  var max = null;
  for (var i=0; i<classes.length; i++) {
    var result = classes[i].match(/max(\d+)/);
    if (result != null) {
      max = result[1];
      break;
    }
  }
  return max;
}

$(function() {
  $("textarea[class~='cf_wordcounter']").each(function() {
    var max = cf_counter.get_max_count(this);
    if (max == null) {
      return;
    }
    $(this).bind("keydown", function() {
      var val = $(this).val();
      var len = val.split(/[\s]+/);
      var field_name = $(this).attr("name");
      var num_words  = len.length - 1;
      if (num_words > max) {
        var allowed_words = val.split(/[\s]+/, max);
        truncated_str = allowed_words.join(" ");
        $(this).val(truncated_str);
      } else {
        $("#" + field_name + "_counter").find("span").html(parseInt(max) - parseInt(num_words));
      }
    });
    $(this).trigger("keydown");
  });
  $("textarea[class~='cf_textcounter']").each(function() {
    var max = cf_counter.get_max_count(this);
    if (max == null) {
      return;
    }
    $(this).bind("keydown", function() {
      var field_name = $(this).attr("name");
      if (this.value.length > max) {
        this.value = this.value.substring(0, max);
      } else {
        $("#" + field_name + "_counter").find("span").html(max - this.value.length);
      }
    });
    $(this).trigger("keydown");
  });
});
END;

    $cft_field_types["textarea"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.word_textarea}",
            "field_type_identifier"          => "textarea",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "textarea",
            "compatible_field_sizes"         => "medium,large,very_large",
            "view_field_rendering_type"      => "smarty",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "",
            "view_field_smarty_markup"       => $textarea_view_field,
            "edit_field_smarty_markup"       => $textarea_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => $textarea_css,
            "resources_js"                   => $textarea_js
        ),

        "settings" => array(

            // Height
            array(
                "field_label"              => "{\$LANG.word_height}",
                "field_setting_identifier" => "height",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "cf_size_small",

                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.phrase_tiny_30px}",
                        "option_value"      => "cf_size_tiny",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_small_80px}",
                        "option_value"      => "cf_size_small",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_medium_150px}",
                        "option_value"      => "cf_size_medium",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_large_300px}",
                        "option_value"      => "cf_size_large",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Highlight
            array(
                "field_label"              => "{\$LANG.phrase_highlight_colour}",
                "field_setting_identifier" => "highlight_colour",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",

                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.word_none}",
                        "option_value"      => "",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_red}",
                        "option_value"      => "cf_colour_red",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_orange}",
                        "option_value"      => "cf_colour_orange",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_yellow}",
                        "option_value"      => "cf_colour_yellow",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_green}",
                        "option_value"      => "cf_colour_green",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_blue}",
                        "option_value"      => "cf_colour_blue",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Input Length
            array(
                "field_label"              => "{\$LANG.phrase_input_length}",
                "field_setting_identifier" => "input_length",
                "field_type"               => "radios",
                "field_orientation"        => "horizontal",
                "default_value_type"       => "static",
                "default_value"            => "",

                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.phrase_no_limit}",
                        "option_value"      => "",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_words}",
                        "option_value"      => "words",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_characters}",
                        "option_value"      => "chars",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // - Max length (words / chars)
            array(
                "field_label"              => "{\$LANG.phrase_max_length_words_chars}",
                "field_setting_identifier" => "maxlength",
                "field_type"               => "textbox",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------


    $password_edit_field =<<< END
<input type="password" name="{\$NAME}" value="{\$VALUE|escape}" class="cf_password" />
{if \$comments}
  <div class="cf_field_comments">{\$comments}</div>
{/if}
END;

    $cft_field_types["password"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.word_password}",
            "field_type_identifier"          => "password",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "password",
            "compatible_field_sizes"         => "1char,2chars,tiny,small,medium",
            "view_field_rendering_type"      => "none",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "",
            "view_field_smarty_markup"       => "",
            "edit_field_smarty_markup"       => $password_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => "input.cf_password {\r\n  width: 120px;\r\n}",
            "resources_js"                   => ""
        ),

        "settings" => array(

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $dropdown_view_field =<<< END
{strip}{if \$contents != ""}
  {foreach from=\$contents.options item=curr_group_info name=group}
    {assign var=options value=\$curr_group_info.options}
    {foreach from=\$options item=option name=row}
      {if \$VALUE == \$option.option_value}{\$option.option_name}{/if}
    {/foreach}
  {/foreach}
{/if}{/strip}
END;

    $dropdown_edit_field =<<< END
{if \$contents == ""}
  <div class="cf_field_comments">
    {\$LANG.phrase_not_assigned_to_option_list}
  </div>
{else}
  <select name="{\$NAME}">
    {foreach from=\$contents.options item=curr_group_info name=group}
      {assign var=group_info value=\$curr_group_info.group_info}
      {assign var=options value=\$curr_group_info.options}
      {if \$group_info.group_name}
      <optgroup label="{\$group_info.group_name|escape}">
      {/if}
      {foreach from=\$options item=option name=row}
        <option value="{\$option.option_value}"
          {if \$VALUE == \$option.option_value}selected{/if}>{\$option.option_name}</option>
      {/foreach}
      {if \$group_info.group_name}
      </optgroup>
      {/if}
    {/foreach}
  </select>
{/if}
{if \$comments}
  <div class="cf_field_comments">{\$comments}</div>
{/if}
END;

    $cft_field_types["dropdown"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.word_dropdown}",
            "field_type_identifier"          => "dropdown",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "select",
            "compatible_field_sizes"         => "1char,2chars,tiny,small,medium,large",
            "view_field_rendering_type"      => "php",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "FieldTypes::displayFieldTypeDropdown",
            "view_field_smarty_markup"       => $dropdown_view_field,
            "edit_field_smarty_markup"       => $dropdown_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => "",
            "resources_js"                   => ""
        ),

        "settings" => array(

            // Option List / Contents
            array(
                "use_for_option_list_map"  => true,
                "field_label"              => "{\$LANG.phrase_option_list_or_contents}",
                "field_setting_identifier" => "contents",
                "field_type"               => "option_list_or_form_field",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $multi_select_view_field =<<< END
{if \$contents != ""}
  {assign var=vals value="`\$g_multi_val_delimiter`"|explode:\$VALUE}
  {assign var=is_first value=true}
  {strip}
    {foreach from=\$contents.options item=curr_group_info name=group}
      {assign var=options value=\$curr_group_info.options}
      {foreach from=\$options item=option name=row}
        {if \$option.option_value|in_array:\$vals}
          {if \$is_first == false}, {/if}
          {\$option.option_name}
          {assign var=is_first value=false}
        {/if}
      {/foreach}
    {/foreach}
  {/strip}
{/if}
END;

    $multi_select_edit_field =<<< END
{if \$contents == ""}
  <div class="cf_field_comments">{\$LANG.phrase_not_assigned_to_option_list}</div>
{else}
  {assign var=vals value="`\$g_multi_val_delimiter`"|explode:\$VALUE}
  <select name="{\$NAME}[]" multiple size="{if \$num_rows}{\$num_rows}{else}5{/if}">
  {foreach from=\$contents.options item=curr_group_info name=group}
    {assign var=group_info value=\$curr_group_info.group_info}
    {assign var=options value=\$curr_group_info.options}
    {if \$group_info.group_name}
    <optgroup label="{\$group_info.group_name|escape}">
    {/if}
    {foreach from=\$options item=option name=row}
      <option value="{\$option.option_value}" {if \$option.option_value|in_array:\$vals}selected{/if}>{\$option.option_name}</option>
    {/foreach}
    {if \$group_info.group_name}
    </optgroup>
    {/if}
  {/foreach}
  </select>
{/if}

{if \$comments}
  <div class="cf_field_comments">{\$comments}</div>
{/if}
END;

    $cft_field_types["multi_select_dropdown"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.phrase_multi_select_dropdown}",
            "field_type_identifier"          => "multi_select_dropdown",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "multi-select",
            "compatible_field_sizes"         => "1char,2chars,tiny,small,medium,large",
            "view_field_rendering_type"      => "php",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "FieldTypes::displayFieldTypeMultiSelectDropdown",
            "view_field_smarty_markup"       => $multi_select_view_field,
            "edit_field_smarty_markup"       => $multi_select_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => "",
            "resources_js"                   => ""
        ),

        "settings" => array(

            // Option List / Contents
            array(
                "use_for_option_list_map"  => true,
                "field_label"              => "{\$LANG.phrase_option_list_or_contents}",
                "field_setting_identifier" => "contents",
                "field_type"               => "option_list_or_form_field",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            ),

            // Num Rows
            array(
                "field_label"              => "{\$LANG.phrase_num_rows}",
                "field_setting_identifier" => "num_rows",
                "field_type"               => "textbox",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "5",
                "options"                  => array()
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}[]",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $radio_view_field =<<< END
{strip}{if \$contents != ""}
  {foreach from=\$contents.options item=curr_group_info name=group}
    {assign var=options value=\$curr_group_info.options}
    {foreach from=\$options item=option name=row}
      {if \$VALUE == \$option.option_value}{\$option.option_name}{/if}
    {/foreach}
  {/foreach}
{/if}{/strip}
END;

    $radio_edit_field =<<< END
{if \$contents == ""}
  <div class="cf_field_comments">{\$LANG.phrase_not_assigned_to_option_list}</div>
{else}
  {assign var=is_in_columns value=false}
  {if \$formatting == "cf_option_list_2cols" || 
      \$formatting == "cf_option_list_3cols" || 
      \$formatting == "cf_option_list_4cols"}
    {assign var=is_in_columns value=true}
  {/if}

  {assign var=counter value="1"}
  {foreach from=\$contents.options item=curr_group_info name=group}
    {assign var=group_info value=\$curr_group_info.group_info}
    {assign var=options value=\$curr_group_info.options}
    {if \$group_info.group_name}
    <div class="cf_option_list_group_label">{\$group_info.group_name}</div>
    {/if}
    {if \$is_in_columns}<div class="{\$formatting}">{/if}
    
    {foreach from=\$options item=option name=row}
      {if \$is_in_columns}<div class="column">{/if}
      <input type="radio" name="{\$NAME}" id="{\$NAME}_{\$counter}" value="{\$option.option_value}"
        {if \$VALUE == \$option.option_value}checked{/if} />
      <label for="{\$NAME}_{\$counter}">{\$option.option_name}</label>
      {if \$is_in_columns}</div>{/if}
      {if \$formatting == "vertical"}<br />{/if}
      
      {assign var=counter value=\$counter+1}
    {/foreach}
    
    {if \$is_in_columns}</div>{/if}
  {/foreach}
  {if \$comments}<div class="cf_field_comments">{\$comments}</div>{/if}
{/if}
END;

    $cft_field_types["radio_buttons"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.phrase_radio_buttons}",
            "field_type_identifier"          => "radio_buttons",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "radio-buttons",
            "compatible_field_sizes"         => "1char,2chars,tiny,small,medium,large",
            "view_field_rendering_type"      => "php",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "FieldTypes::displayFieldTypeRadios",
            "view_field_smarty_markup"       => $radio_view_field,
            "edit_field_smarty_markup"       => $radio_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => "/* All CSS styles for this field type are found in Shared Resources */",
            "resources_js"                   => ""
        ),

        "settings" => array(

            // Option List / Contents
            array(
                "use_for_option_list_map"  => true,
                "field_label"              => "{\$LANG.phrase_option_list_or_contents}",
                "field_setting_identifier" => "contents",
                "field_type"               => "option_list_or_form_field",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            ),

            // Formatting
            array(
                "field_label"              => "{\$LANG.word_formatting}",
                "field_setting_identifier" => "formatting",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "horizontal",

                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.word_horizontal}",
                        "option_value"      => "horizontal",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_vertical}",
                        "option_value"      => "vertical",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_2_columns}",
                        "option_value"      => "cf_option_list_2cols",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_3_columns}",
                        "option_value"      => "cf_option_list_3cols",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_4_columns}",
                        "option_value"      => "cf_option_list_4cols",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $checkboxes_view_field =<<< END
{strip}{if \$contents != ""}
  {assign var=vals value="`\$g_multi_val_delimiter`"|explode:\$VALUE}
  {assign var=is_first value=true}
  {strip}
    {foreach from=\$contents.options item=curr_group_info name=group}
      {assign var=options value=\$curr_group_info.options}
      {foreach from=\$options item=option name=row}
        {if \$option.option_value|in_array:\$vals}
          {if \$is_first == false}, {/if}
          {\$option.option_name}
          {assign var=is_first value=false}
        {/if}
      {/foreach}
    {/foreach}
  {/strip}
{/if}{/strip}
END;

    $checkboxes_edit_field =<<< END
{if \$contents == ""}
  <div class="cf_field_comments">{\$LANG.phrase_not_assigned_to_option_list}</div>
{else}
  {assign var=vals value="`\$g_multi_val_delimiter`"|explode:\$VALUE}
  {assign var=is_in_columns value=false}
  {if \$formatting == "cf_option_list_2cols" || 
      \$formatting == "cf_option_list_3cols" ||
      \$formatting == "cf_option_list_4cols"}
    {assign var=is_in_columns value=true}
  {/if}
  
  {assign var=counter value="1"}
  {foreach from=\$contents.options item=curr_group_info name=group}
    {assign var=group_info value=\$curr_group_info.group_info}
    {assign var=options value=\$curr_group_info.options}
    
    {if \$group_info.group_name}
      <div class="cf_option_list_group_label">{\$group_info.group_name}</div>
    {/if}
    {if \$is_in_columns}<div class="{\$formatting}">{/if}
    
    {foreach from=\$options item=option name=row}
      {if \$is_in_columns}<div class="column">{/if}
      <input type="checkbox" name="{\$NAME}[]" id="{\$NAME}_{\$counter}"
        value="{\$option.option_value|escape}" {if \$option.option_value|in_array:\$vals}checked{/if} />
      <label for="{\$NAME}_{\$counter}">{\$option.option_name}</label>
      {if \$is_in_columns}</div>{/if}
      {if \$formatting == "vertical"}<br />{/if}
      
      {assign var=counter value=\$counter+1}
    {/foreach}
    
    {if \$is_in_columns}</div>{/if}
  {/foreach}

  {if \$comments}
    <div class="cf_field_comments">{\$comments}</div>
  {/if}
{/if}
END;


    $cft_field_types["checkboxes"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.word_checkboxes}",
            "field_type_identifier"          => "checkboxes",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "checkboxes",
            "compatible_field_sizes"         => "1char,2chars,tiny,small,medium,large",
            "view_field_rendering_type"      => "php",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "FieldTypes::displayFieldTypeCheckboxes",
            "view_field_smarty_markup"       => $checkboxes_view_field,
            "edit_field_smarty_markup"       => $checkboxes_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => "/* all CSS is found in Shared Resources */",
            "resources_js"                   => ""
        ),

        "settings" => array(

            // Option List / Contents
            array(
                "use_for_option_list_map"  => true,
                "field_label"              => "{\$LANG.phrase_option_list_or_contents}",
                "field_setting_identifier" => "contents",
                "field_type"               => "option_list_or_form_field",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            ),

            // Formatting
            array(
                "field_label"              => "{\$LANG.word_formatting}",
                "field_setting_identifier" => "formatting",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "horizontal",

                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.word_horizontal}",
                        "option_value"      => "horizontal",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_vertical}",
                        "option_value"      => "vertical",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_2_columns}",
                        "option_value"      => "cf_option_list_2cols",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_3_columns}",
                        "option_value"      => "cf_option_list_3cols",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_4_columns}",
                        "option_value"      => "cf_option_list_4cols",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}[]",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $date_view_field =<<< END
{strip}
{if \$VALUE}
  {assign var=tzo value=""}
  {if \$apply_timezone_offset == "yes"}
    {assign var=tzo value=\$ACCOUNT_INFO.timezone_offset}
  {/if}
  {if \$display_format == "yy-mm-dd" || !\$display_format}
    {\$VALUE|custom_format_date:\$tzo:"Y-m-d"}
  {elseif \$display_format == "dd/mm/yy"}
    {\$VALUE|custom_format_date:\$tzo:"d/m/Y"}
  {elseif \$display_format == "mm/dd/yy"}
    {\$VALUE|custom_format_date:\$tzo:"m/d/Y"}
  {elseif \$display_format == "M d, yy"}
    {\$VALUE|custom_format_date:\$tzo:"M j, Y"}
  {elseif \$display_format == "MM d, yy"}
    {\$VALUE|custom_format_date:\$tzo:"F j, Y"}
  {elseif \$display_format == "D M d, yy"}
    {\$VALUE|custom_format_date:\$tzo:"D M j, Y"}
  {elseif \$display_format == "DD, MM d, yy"}
    {\$VALUE|custom_format_date:\$tzo:"l M j, Y"}
  {elseif \$display_format == "dd. mm. yy."}
    {\$VALUE|custom_format_date:\$tzo:"d. m. Y."}
  {elseif \$display_format == "datetime:dd/mm/yy|h:mm TT|ampm`true"}
    {\$VALUE|custom_format_date:\$tzo:"d/m/Y g:i A"}
  {elseif \$display_format == "datetime:mm/dd/yy|h:mm TT|ampm`true"}
    {\$VALUE|custom_format_date:\$tzo:"m/d/Y g:i A"}
  {elseif \$display_format == "datetime:yy-mm-dd|h:mm TT|ampm`true"}
    {\$VALUE|custom_format_date:\$tzo:"Y-m-d g:i A"}
  {elseif \$display_format == "datetime:yy-mm-dd|hh:mm"}
    {\$VALUE|custom_format_date:\$tzo:"Y-m-d H:i"}
  {elseif \$display_format == "datetime:yy-mm-dd|hh:mm:ss|showSecond`true"}
    {\$VALUE|custom_format_date:\$tzo:"Y-m-d H:i:s"}
  {elseif \$display_format == "datetime:dd. mm. yy.|hh:mm"}
    {\$VALUE|custom_format_date:\$tzo:"d. m. Y. H:i"}
  {/if}
{/if}
{/strip}
END;

    $date_edit_field =<<< END
{assign var=class value="cf_datepicker"}
{if \$display_format|strpos:"datetime" === 0}
  {assign var=class value="cf_datetimepicker"}
{/if}

{assign var="val" value=""}
{if \$VALUE}
  {assign var=tzo value=""}
  {if \$apply_timezone_offset == "yes"}
    {assign var=tzo value=\$ACCOUNT_INFO.timezone_offset}
  {/if}
  {if \$display_format == "yy-mm-dd"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"Y-m-d"}
  {elseif \$display_format == "dd/mm/yy"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"d/m/Y"}
  {elseif \$display_format == "mm/dd/yy"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"m/d/Y"}
  {elseif \$display_format == "M d, yy"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"M j, Y"}
  {elseif \$display_format == "MM d, yy"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"F j, Y"}
  {elseif \$display_format == "D M d, yy"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"D M j, Y"}
  {elseif \$display_format == "DD, MM d, yy"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"l M j, Y"}
  {elseif \$display_format == "dd. mm. yy."}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"d. m. Y."}
  {elseif \$display_format == "datetime:dd/mm/yy|h:mm TT|ampm`true"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"d/m/Y g:i A"}
  {elseif \$display_format == "datetime:mm/dd/yy|h:mm TT|ampm`true"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"m/d/Y g:i A"}
  {elseif \$display_format == "datetime:yy-mm-dd|h:mm TT|ampm`true"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"Y-m-d g:i A"}
  {elseif \$display_format == "datetime:yy-mm-dd|hh:mm"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"Y-m-d H:i"}
  {elseif \$display_format == "datetime:yy-mm-dd|hh:mm:ss|showSecond`true"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"Y-m-d H:i:s"}
  {elseif \$display_format == "datetime:dd. mm. yy.|hh:mm"}
    {assign var=val value=\$VALUE|custom_format_date:\$tzo:"d. m. Y. H:i"}
  {/if}
{/if}

<div class="cf_date_group">
  <input type="input" name="{\$NAME}" id="{\$NAME}_id" class="cf_datefield {\$class}"
    value="{\$val}" /><img class="ui-datepicker-trigger" src="{\$g_root_url}/global/images/calendar.png" 
    id="{\$NAME}_icon_id" />
  <input type="hidden" id="{\$NAME}_format" value="{\$display_format}" />
  {if \$comments}
    <div class="cf_field_comments">{\$comments}</div>
  {/if}
</div>
END;

    $date_php_processing =<<< END
\$field_name     = \$vars["field_info"]["field_name"];
\$date           = \$vars["data"][\$field_name];
\$display_format = \$vars["settings"]["display_format"];
\$atzo           = \$vars["settings"]["apply_timezone_offset"];
\$account_info   = isset(\$vars["account_info"]) ? \$vars["account_info"] : array();

if (empty(\$date)) {
  \$value = "";
} else { 
  if (strpos(\$display_format, "datetime:") === 0) {
    \$parts = explode(" ", \$date);
    switch (\$display_format) {
      case "datetime:dd/mm/yy|h:mm TT|ampm`true":
        \$date = substr(\$date, 3, 2) . "/" . substr(\$date, 0, 2) . "/" . substr(\$date, 6);
        break;
      case "datetime:dd. mm. yy.|hh:mm":
        \$date = substr(\$date, 4, 2) . "/" . substr(\$date, 0, 2) . "/" . substr(\$date, 8, 4) . " " . substr(\$date, 14);
        break;
    }
  } else {
    if (\$display_format == "dd/mm/yy") {
      \$date = substr(\$date, 3, 2) . "/" . substr(\$date, 0, 2) . "/" . substr(\$date, 6);
    } else if (\$display_format == "dd. mm. yy.") {
      \$parts = explode(\" \", \$date);
      \$date = trim(\$parts[1], ".") . "/" . trim(\$parts[0], ".") . "/" . trim(\$parts[2], ".");
    }
  }
  \$time = strtotime(\$date);
  
  // lastly, if this field has a timezone offset being applied to it, do the
  // appropriate math on the date
  if (\$atzo == "yes" && !isset(\$account_info["timezone_offset"])) {
    \$seconds_offset = \$account_info["timezone_offset"] * 60 * 60;
    \$time += \$seconds_offset;
  }
  \$value = date(\"Y-m-d H:i:s\", \$time);
}
END;

    $date_css =<<< END
.cf_datepicker {
  width: 160px;
}
.cf_datetimepicker {
  width: 160px;
}
.ui-datepicker-trigger {
  cursor: pointer;
}
END;

    $date_js =<<< END
$(function() {
  // the datetimepicker has a bug that prevents the icon from appearing. So
  // instead, we add the image manually into the page and assign the open event
  // handler to the image
  var default_settings = {
    changeYear: true,
    changeMonth: true
  }
  $(".cf_datepicker").each(function() {
    var field_name = $(this).attr("name");
    var settings = default_settings;
    if ($("#" + field_name + "_id").length) {
      settings.dateFormat = $("#" + field_name + "_format").val();
    }
    $(this).datepicker(settings);
    $("#" + field_name + "_icon_id").bind("click", { field_id: "#" + field_name + "_id" }, function(e) {
      $.datepicker._showDatepicker($(e.data.field_id)[0]);
    });
  });
  
  $(".cf_datetimepicker").each(function() {
    var field_name = $(this).attr("name");
    var settings = default_settings;

    if ($("#" + field_name + "_id").length) {
      var settings_str = $("#" + field_name + "_format").val();
      settings_str = settings_str.replace(/datetime:/, "");
      var settings_list = settings_str.split("|");
      var settings = {};
      settings.dateFormat = settings_list[0];
      settings.timeFormat = settings_list[1];
      for (var i=2; i<settings_list.length; i++) {
        var parts = settings_list[i].split("`");
        if (parts[1] === "true") {
          parts[1] = true;
        }
        settings[parts[0]] = parts[1];
      }
    }
    
    $(this).datetimepicker(settings);
    $("#" + field_name + "_icon_id").bind("click", { 
      field_id: "#" + field_name + "_id"
    }, function(e) {
      $.datepicker._showDatepicker($(e.data.field_id)[0]);
    });
  });
});
END;


    $cft_field_types["date"] = array(
        "field_type" => array(
            "is_editable"                    => "no",
            "non_editable_info"              => "'{\$LANG.text_non_deletable_fields}'",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.word_date}",
            "field_type_identifier"          => "date",
            "is_file_field"                  => "no",
            "is_date_field"                  => "yes",
            "raw_field_type_map"             => "",
            "compatible_field_sizes"         => "small",
            "view_field_rendering_type"      => "php",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "FieldTypes::displayFieldTypeDate",
            "view_field_smarty_markup"       => $date_view_field,
            "edit_field_smarty_markup"       => $date_edit_field,
            "php_processing"                 => $date_php_processing,
            "resources_css"                  => $date_css,
            "resources_js"                   => $date_js
        ),

        "settings" => array(

            // Custom Display Format
            array(
                "field_label"              => "{\$LANG.phrase_custom_display_format}",
                "field_setting_identifier" => "display_format",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "yy-mm-dd",

                "options" => array(
                    array(
                        "option_text"       => "2011-11-30",
                        "option_value"      => "yy-mm-dd",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "30/11/2011 (dd/mm/yyyy)",
                        "option_value"      => "dd/mm/yy",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "11/30/2011 (mm/dd/yyyy)",
                        "option_value"      => "mm/dd/yy",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "Nov 30, 2011",
                        "option_value"      => "M d, yy",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "November 30, 2011",
                        "option_value"      => "MM d, yy",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "Wed Nov 30, 2011",
                        "option_value"      => "D M d, yy",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "Wednesday, November 30, 2011",
                        "option_value"      => "DD, MM d, yy",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "30. 08. 2011.",
                        "option_value"      => "dd. mm. yy.",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "30/11/2011 8:00 PM",
                        "option_value"      => "datetime:dd/mm/yy|h:mm TT|ampm`true",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "11/30/2011 8:00 PM",
                        "option_value"      => "datetime:mm/dd/yy|h:mm TT|ampm`true",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "2011-11-30 8:00 PM",
                        "option_value"      => "datetime:yy-mm-dd|h:mm TT|ampm`true",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "2011-11-30 20:00",
                        "option_value"      => "datetime:yy-mm-dd|hh:mm",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "2011-11-30 20:00:00",
                        "option_value"      => "datetime:yy-mm-dd|hh:mm:ss|showSecond`true",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "30. 08. 2011. 20:00",
                        "option_value"      => "datetime:dd. mm. yy.|hh:mm",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Apply Timezone Offset
            array(
                "field_label"              => "{\$LANG.phrase_apply_timezone_offset}",
                "field_setting_identifier" => "apply_timezone_offset",
                "field_type"               => "radios",
                "field_orientation"        => "horizontal",
                "default_value_type"       => "static",
                "default_value"            => "no",
                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.word_yes}",
                        "option_value"      => "yes",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.word_no}",
                        "option_value"      => "no",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------


    $time_edit_field =<<< END
<div class="cf_date_group">
  <input type="input" name="{\$NAME}" value="{\$VALUE}" class="cf_datefield cf_timepicker" />
  <input type="hidden" id="{\$NAME}_id" value="{\$display_format}" />
  {if \$comments}
    <div class="cf_field_comments">{\$comments}</div>
  {/if}
</div>
END;

    $time_css =<<< END
.cf_timepicker {
  width: 60px;
}
.ui-timepicker-div .ui-widget-header {
  margin-bottom: 8px;
}
.ui-timepicker-div dl {
  text-align: left;
}
.ui-timepicker-div dl dt {
  height: 25px;
}
.ui-timepicker-div dl dd {
  margin: -25px 0 10px 65px;
}
.ui-timepicker-div td {
  font-size: 90%;
}
END;

    $time_js =<<< END
$(function() {
  var default_settings = {
    buttonImage:     g.root_url + "/global/images/clock.png",
    showOn:          "both",
    buttonImageOnly: true
  }
  $(".cf_timepicker").each(function() {
    var field_name = $(this).attr("name");
    var settings = default_settings;
    if ($("#" + field_name + "_id").length) {
      var settings_list = $("#" + field_name + "_id").val().split("|");
      if (settings_list.length > 0) {
        settings.timeFormat = settings_list[0];
        for (var i=1; i<settings_list.length; i++) {
          var parts = settings_list[i].split("`");
          if (parts[1] === "true") {
            parts[1] = true;
          } else if (parts[1] === "false") {
            parts[1] = false;
          }
          settings[parts[0]] = parts[1];
        }
      }
    }
    $(this).timepicker(settings);
  });
});
END;



    $cft_field_types["time"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.word_time}",
            "field_type_identifier"          => "time",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "",
            "compatible_field_sizes"         => "small",
            "view_field_rendering_type"      => "none",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "",
            "view_field_smarty_markup"       => "",
            "edit_field_smarty_markup"       => $time_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => $time_css,
            "resources_js"                   => $time_js
        ),

        "settings" => array(

            // Custom Display Format
            array(
                "field_label"              => "{\$LANG.phrase_custom_display_format}",
                "field_setting_identifier" => "display_format",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "h:mm TT|ampm`true",

                "options" => array(
                    array(
                        "option_text"       => "8:00 AM",
                        "option_value"      => "h:mm TT|ampm`true",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "16:00",
                        "option_value"      => "hh:mm|ampm`false",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "16:00:00",
                        "option_value"      => "hh:mm:ss|showSecond`true|ampm`false",
                        "is_new_sort_group" => "yes"
                    ),
                )
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "required",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "{\$field_name}",
                "custom_function"          => "",
                "custom_function_required" => "na",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $phone_view_field =<<< END
{php}
\$format = \$this->get_template_vars("phone_number_format");
\$values = explode("|", \$this->get_template_vars("VALUE"));
\$pieces = preg_split("/(x+)/", \$format, 0, PREG_SPLIT_DELIM_CAPTURE);
\$counter = 1;
\$output = "";
\$has_content = false;
foreach (\$pieces as \$piece) {
  if (empty(\$piece)) {
    continue;
  }
  if (\$piece[0] == "x") {
    \$value = (isset(\$values[\$counter-1])) ? \$values[\$counter-1] : "";
    \$output .= \$value;
    if (!empty(\$value)) {
      \$has_content = true;
    }
    \$counter++;
  } else {
    \$output .= \$piece;
  }
}

if (!empty(\$output) && \$has_content) {
  echo \$output;
}
{/php}
END;

    $phone_edit_field =<<< END
{php}
\$format = \$this->get_template_vars("phone_number_format");
\$values = explode("|", \$this->get_template_vars("VALUE"));
\$name   = \$this->get_template_vars("NAME");
\$pieces = preg_split("/(x+)/", \$format, 0, PREG_SPLIT_DELIM_CAPTURE);
\$counter = 1;

foreach (\$pieces as \$piece) {
  if (strlen(\$piece) == 0) {
    continue;
  }
  if (\$piece[0] == "x") {
    \$size = strlen(\$piece);
    \$value = (isset(\$values[\$counter-1])) ? \$values[\$counter-1] : "";
    \$value = htmlspecialchars(\$value);
    echo "<input type=\"text\" name=\"{\$name}_\$counter\" value=\"\$value\" size=\"\$size\" maxlength="\$size\" />";
    \$counter++;
  } else {
    echo \$piece;
  }
}
{/php}
{if \$comments}
  <div class="cf_field_comments">{\$comments}</div>
{/if}
END;

    $phone_php_processing =<<< END
\$field_name = \$vars["field_info"]["field_name"];
\$joiner = "|";

\$count = 1;
\$parts = array();
while (isset(\$vars["data"]["{\$field_name}_\$count"])) {
  \$parts[] = \$vars["data"]["{\$field_name}_\$count"];
  \$count++;
}
\$value = implode("|", \$parts);
END;

    $phone_js =<<< END
var cf_phone = {};
cf_phone.check_required = function() {
  var errors = [];
  for (var i=0; i<rsv_custom_func_errors.length; i++) {
    if (rsv_custom_func_errors[i].func != "cf_phone.check_required") {
      continue;
    }
    var field_name = rsv_custom_func_errors[i].field;
    var fields = $("input[name^=\"" + field_name + "_\"]");
    fields.each(function() {
      if (!this.name.match(/_(\d+)$/)) {
        return;
      }
      var req_len = $(this).attr("maxlength");
      var actual_len = this.value.length;
      if (req_len != actual_len || this.value.match(/\D/)) {
        var el = document.edit_submission_form[field_name];
        errors.push([el, rsv_custom_func_errors[i].err]);
        return false;
      }
    });
  }
  if (errors.length) {
    return errors;
  }
  
  return true;
}
END;

    $cft_field_types["phone"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.phrase_phone_number}",
            "field_type_identifier"          => "phone",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "",
            "compatible_field_sizes"         => "small,medium",
            "view_field_rendering_type"      => "php",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "FieldTypes::displayFieldTypePhoneNumber",
            "view_field_smarty_markup"       => $phone_view_field,
            "edit_field_smarty_markup"       => $phone_edit_field,
            "php_processing"                 => $phone_php_processing,
            "resources_css"                  => "",
            "resources_js"                   => $phone_js
        ),

        "settings" => array(

            // Phone Number Format
            array(
                "field_label"              => "{\$LANG.phrase_phone_number_format}",
                "field_setting_identifier" => "phone_number_format",
                "field_type"               => "textbox",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "(xxx) xxx-xxxx",
                "options"                  => array()
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "function",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "",
                "custom_function"          => "cf_phone.check_required",
                "custom_function_required" => "yes",
                "default_error_message"    => "{\$LANG.validation_default_phone_num_required}"
            )
        )
    );


    // ------------------------------------------------------------------------------------------------

    $code_view_field =<<< END
{if \$CONTEXTPAGE == "edit_submission"}

  <textarea id="{\$NAME}_id" name="{\$NAME}">{\$VALUE}</textarea>
  <script>
  var code_mirror_{\$NAME} = new CodeMirror.fromTextArea("{\$NAME}_id", 
  {literal}{{/literal} height: "{\$SIZE_PX}px", path: "{\$g_root_url}/global/codemirror/js/",
  readOnly: true,
  {if \$code_markup == "HTML" || \$code_markup == "XML"}
    parserfile: ["parsexml.js"],
    stylesheet: "{\$g_root_url}/global/codemirror/css/xmlcolors.css"
  {elseif \$code_markup == "CSS"}
    parserfile: ["parsecss.js"],
    stylesheet: "{\$g_root_url}/global/codemirror/css/csscolors.css"
  {elseif \$code_markup == "JavaScript"}
    parserfile: ["tokenizejavascript.js", "parsejavascript.js"],
    stylesheet: "{\$g_root_url}/global/codemirror/css/jscolors.css"
  {/if}
  {literal}});{/literal}
  </script>
  
{else}
  {\$VALUE|strip_tags}
{/if}
END;

    $code_edit_field =<<< END
<div class="editor">
  <textarea id="{\$NAME}_id" name="{\$NAME}">{\$VALUE}</textarea>
</div>

<script>
var code_mirror_{\$NAME} = new CodeMirror.fromTextArea("{\$NAME}_id", {literal}{{/literal}
  height: "{\$height}px",
  path:   "{\$g_root_url}/global/codemirror/js/",
  {if \$code_markup == "HTML" || \$code_markup == "XML"} 
    parserfile: ["parsexml.js"],
    stylesheet: "{\$g_root_url}/global/codemirror/css/xmlcolors.css"
  {elseif \$code_markup == "CSS"}
    parserfile: ["parsecss.js"],
    stylesheet: "{\$g_root_url}/global/codemirror/css/csscolors.css"
  {elseif \$code_markup == "JavaScript"}
    parserfile: ["tokenizejavascript.js", "parsejavascript.js"],
    stylesheet: "{\$g_root_url}/global/codemirror/css/jscolors.css"
  {/if}
  {literal}});{/literal}
</script>

{if \$comments}
  <div class="cf_field_comments">{\$comments}</div>
{/if}
END;

    $code_js =<<< END
var cf_code = {};
cf_code.check_required = function() {
  var errors = [];
  for (var i=0; i<rsv_custom_func_errors.length; i++) {
    if (rsv_custom_func_errors[i].func != "cf_code.check_required") {
      continue;
    }
    var field_name = rsv_custom_func_errors[i].field;
    var val = $.trim(window["code_mirror_" + field_name].getCode());
    if (!val) {
      var el = document.edit_submission_form[field_name];
      errors.push([el, rsv_custom_func_errors[i].err]);
    }
  }
  if (errors.length) {
    return errors;
  }
  return true;
}"
END;


    $cft_field_types["code_markup"] = array(
        "field_type" => array(
            "is_editable"                    => "yes",
            "non_editable_info"              => "NULL",
            "managed_by_module_id"           => "NULL",
            "field_type_name"                => "{\$LANG.phrase_code_markup_field}",
            "field_type_identifier"          => "code_markup",
            "is_file_field"                  => "no",
            "is_date_field"                  => "no",
            "raw_field_type_map"             => "textarea",
            "compatible_field_sizes"         => "large,very_large",
            "view_field_rendering_type"      => "php",
            "view_field_php_function_source" => "core",
            "view_field_php_function"        => "FieldTypes::displayFieldTypeCodeMarkup",
            "view_field_smarty_markup"       => $code_view_field,
            "edit_field_smarty_markup"       => $code_edit_field,
            "php_processing"                 => "",
            "resources_css"                  => ".cf_view_markup_field {\r\n  margin: 0px; \r\n}",
            "resources_js"                   => $code_js
        ),

        "settings" => array(

            // Code / Markup Type
            array(
                "field_label"              => "{\$LANG.phrase_code_markup_type}",
                "field_setting_identifier" => "code_markup",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "HTML",

                "options" => array(
                    array(
                        "option_text"       => "CSS",
                        "option_value"      => "CSS",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "HTML",
                        "option_value"      => "HTML",
                        "is_new_sort_group" => "yes"
                        ),
                    array(
                        "option_text"       => "JavaScript",
                        "option_value"      => "JavaScript",
                        "is_new_sort_group" => "yes"
                        ),
                    array(
                        "option_text"       => "XML",
                        "option_value"      => "XML",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Height
            array(
                "field_label"              => "{\$LANG.word_height}",
                "field_setting_identifier" => "height",
                "field_type"               => "select",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "200",

                "options" => array(
                    array(
                        "option_text"       => "{\$LANG.phrase_tiny_50px}",
                        "option_value"      => "50",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_small_100px}",
                        "option_value"      => "100",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_medium_200px}",
                        "option_value"      => "200",
                        "is_new_sort_group" => "yes"
                    ),
                    array(
                        "option_text"       => "{\$LANG.phrase_large_400px}",
                        "option_value"      => "400",
                        "is_new_sort_group" => "yes"
                    )
                )
            ),

            // Field Comments
            array(
                "field_label"              => "{\$LANG.phrase_field_comments}",
                "field_setting_identifier" => "comments",
                "field_type"               => "textarea",
                "field_orientation"        => "na",
                "default_value_type"       => "static",
                "default_value"            => "",
                "options"                  => array()
            )
        ),

        "validation" => array(
            array(
                "rsv_rule"                 => "function",
                "rule_label"               => "{\$LANG.word_required}",
                "rsv_field_name"           => "",
                "custom_function"          => "cf_code.check_required",
                "custom_function_required" => "yes",
                "default_error_message"    => "{\$LANG.validation_default_rule_required}"
            )
        )
    );

    return $cft_field_types;
}
