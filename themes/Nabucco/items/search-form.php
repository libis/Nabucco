<?php
    if (!empty($formActionUri)):
        $formAttributes['action'] = $formActionUri;
    else:
        $formAttributes['action'] = url(array('controller' => 'items',
            'action' => 'browse'));
    endif;
    $formAttributes['method'] = 'GET';
?>


<form <?php echo tag_attributes($formAttributes); ?>>
    <div id="search-keywords" class="field">
        <?php echo $this->formLabel('keyword-search', __('Search for Keywords')); ?>
        <p class="search-description">
            <?php echo libis_get_simple_page_content('search_info_advanced_1');?>
        </p>
        <div class="inputs">
            <?php
            echo $this->formText(
                    'search', @$_REQUEST['search'], array('id' => 'keyword-search', 'size' => '40')
            );
            ?>
        </div>
    </div>

    <div id="search-narrow-by-fields" class="field">
        <div class="label"><?php echo __('Narrow by Specific Fields'); ?></div>
        <p class="search-description">
            <?php echo libis_get_simple_page_content('search_info_advanced_2');?>
        </p>
        <div class="inputs">
            <?php
            // If the form has been submitted, retain the number of search
            // fields used and rebuild the form
            if (!empty($_GET['advanced'])) {
                $search = $_GET['advanced'];
            } else {
                $search = array(array('field' => '', 'type' => '', 'value' => ''));
            }

            //Here is where we actually build the search form
            foreach ($search as $i => $rows):
            ?>
            <div class="search-entry">
                <?php
                //The POST looks like =>
                // advanced[0] =>
                //[field] = 'description'
                //[type] = 'contains'
                //[terms] = 'foobar'
                //etc
                echo $this->formSelect(
                        "advanced[$i][element_id]", @$rows['element_id'], array(
                    'title' => __("Search Field"),
                    'id' => null,
                    'class' => 'advanced-search-element'
                        ), get_table_options('Element', null, array(
                    'record_types' => array('Item', 'All'),
                    'sort' => 'alphaBySet')
                        )
                );

                echo $this->formSelect(
                        "advanced[$i][type]", @$rows['type'], array(
                    'title' => __("Search Type"),
                    'id' => null,
                    'class' => 'advanced-search-type'
                        ), label_table_options(array(
                    'contains' => __('contains'),
                    'does not contain' => __('does not contain'),
                    'is exactly' => __('is exactly'),
                    'is empty' => __('is empty'),
                    'is not empty' => __('is not empty'))
                        )
                );

                echo $this->formText(
                        "advanced[$i][terms]", @$rows['terms'], array(
                    'size' => '20',
                    'title' => __("Search Terms"),
                    'id' => null,
                    'class' => 'advanced-search-terms'
                        ), findTextPairs(@$rows['element_id'])
                );

                ?>
                <button type="button" class="remove_search" disabled="disabled" style="display: none;"><?php echo __('Remove field'); ?></button>
            </div>
                <?php endforeach; ?>
        </div>
        <button type="button" class="add_search"><?php echo __('Add a Field'); ?></button>
    </div>

<!-- start aanpassing -->
<?php
    $medium_commonly_searched_fields = array(111,120,141,156,162,165,283,286);
    $all_table_options = get_table_options('Element', null, array(
            'record_types' => array('Item', 'All'),
            'sort' => 'alphaBySet')
        );
    $merged_table_options = $all_table_options["Dublin Core"] + $all_table_options["Item Type Metadata"];
?>
<div id="search-by-certain-fields" class="field">
  <?php
  if (!empty($_GET['advanced'])) {
      $search = $_GET['advanced'];
  } else {
      $search = array();
  }
  ?>
    <?php if (!isset($buttonText)) $buttonText = __('Search'); ?>


    <p class="search-description">
        <?php echo libis_get_simple_page_content('search_info_advanced_3');?>
    </p>
    <table>
      <tr>
         <td>
             <div>Month</div>
         </td>
         <td name="advanced[200]" id="advanced-200"></td>
         <td>
             <select name="x" id="advanced-200-terms" title="Select" class="advanced-search-element">
                 <option value="">Select Below </option>
                 <option value="-">-</option>
                 <option value="I">I</option>
                 <option value="II">II</option>
                 <option value="III">III</option>
                 <option value="IV">IV</option>
                 <option value="V">V</option>
                 <option value="VI">VI</option>
                 <option value="VIb">VIb</option>
                 <option value="VII">VII</option>
                 <option value="VIII">VIII</option>
                 <option value="IX">IX</option>
                 <option value="X">X</option>
                 <option value="XI">XI</option>
                 <option value="XII">XII</option>
                 <option value="XIIb">XIIb</option>
              </select>
          </td>
      </tr>
          <?php foreach($medium_commonly_searched_fields as $i => $table_option): ?>
              <tr>
                  <td>
                      <div><?php echo $merged_table_options[$table_option]; ?></div>
                  </td>
                  <?php
                  $i = $i + 99;
                  $basename = "advanced[$i]";
                  $basenameC = "advanced\\\\[$i\\\\]";
                  $basenameID = "advanced-$i";
                  $hidden_element_id = $this->formHidden(
                          $basename . "[element_id]", $table_option, array('hidden' => true));
                  $hidden_type = $this->formHidden(
                          $basename . "[type]", "is exactly", array('hidden' => true));
                  ?>
                  <td name="<?php echo $basename ?>" id="<?php echo $basenameID ?>"></td>
                  <td><?php
                  /*echo $this->formText(
                          $basename . "[terms]", array_key_exists($i, $search) ? $search[$i]["terms"] : "", array("style" => "margin-bottom:0;")
                  );*/

                  echo $this->formSelect(
                        'x', $table_option, array(
                        'title' => __("Select"),
                        'id' => $basenameID.'-terms',
                        'class' => 'advanced-search-element'
                            ), findTextPairs($table_option)
                    );

                  ?></td>
              </tr>
              <script>
                  function addRestFields() {
                      jQuery('#<?php echo $basenameID; ?>-element_id').remove();
                      jQuery('#<?php echo $basenameID; ?>-type').remove();
                      if (jQuery('#<?php echo $basenameID; ?>-terms').val()) {
                          jQuery('#<?php echo $basenameID; ?>-terms').attr("name","<?php echo $basename . "[terms]"; ?>");
                          jQuery('td#<?php echo $basenameID; ?>').append('<?php echo $hidden_element_id; ?>');
                          jQuery('td#<?php echo $basenameID; ?>').append('<?php echo $hidden_type; ?>');
                      }else{
                          jQuery('#<?php echo $basenameID; ?>-terms').attr("name","x");
                      }
                  }
                  jQuery('#<?php echo $basenameID; ?>-terms').change(addRestFields);
                  if (jQuery('#<?php echo $basenameID; ?>-terms').val().length > 0) {
                      addRestFields();
                  }
              </script>
        <?php endforeach; ?>

        <script>
           function addRestFields() {
               jQuery('#advanced-200-element_id').remove();
               jQuery('#advanced-200-type').remove();
               if (jQuery('#advanced-200-terms').val()) {
                   jQuery('#advanced-200-terms').attr("name","advanced[200][terms]");
                   jQuery('td#advanced-200').append('<input type="hidden" name="advanced[200][element_id]" value="129" hidden="1" id="advanced-200-element_id">');
                   jQuery('td#advanced-200').append('<input type="hidden" name="advanced[200][type]" value="is exactly" hidden="1" id="advanced-200-type">');
               }else{
                   jQuery('#advanced-200-terms').attr("name","x");
               }
           }
           jQuery('#advanced-200-terms').change(addRestFields);
           if (jQuery('#advanced-200-terms').val().length > 0) {
               addRestFields();
           }
        </script>
        <tr>
              <td>
                  <div>Type and content</div>
              </td>
              <td name="advanced[1]" id="advanced-1"></td>
              <td>
                <select class="type-select advanced-search-element" name="x" id="stateSel" title="Select" id="" size="1">
                  <option value="" disabled selected>Select</option>
                </select>
                <select class="type-select" name="opttwo" disabled=true id="countySel" size="1">
                </select>
                <select class="type-select" name="optthree" disabled=true id="citySel" size="1">
                </select>
              </td>
          </tr>
        </table>
    </div>

    <!-- Type: Hidden -->
    <?php
      echo $this->formHidden(
              'type', @$_REQUEST['type'], array('id' => 'item-type-search'), get_table_options('ItemType')
      );
    ?>

    <div>
        <?php if (!isset($buttonText)) $buttonText = __('Search'); ?>
        <input type="submit" class="submit" name="submit_search" id="submit_search_advanced" value="<?php echo $buttonText ?>">
        <input type="submit" class="submit" name="submit_search" id="reset_search_advanced" value="Reset">
    </div>
</form>
    <?php echo js_tag('items-search'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.Search.activateSearchButtons();
        jQuery('.search-entry optgroup').replaceWith(function () {
            return jQuery(this).children();
        });
        var blackListElements = [
            "Contributor",
            "Subject",
            "Title",
            "Creator",
            "Date",
            "Description",
            "Publisher",
            "Coverage",
            "Format",
            "Identifier",
            "Language",
            "Relation",
            "Rights",
            "Source",
            "Type",
            "Alternative name",
            "Archive name",
            "Author",
            "BCC",
            "Bibliography ID",
            "Biographical Text",
            "Birth Date",
            "Birthplace",
            "Bit Rate/Frequency",
            "Book",
            "CC",
            "Compression",
            "Death Date",
            "Director",
            "Duration",
            "Editor",
            "Email Body",
            "End date",
            "Entity ID",
            "Event Type",
            "Family",
            "From",
            "Gender",
            "Interviewee",
            "Interviewer",
            "Journal",
            "Archive identifier",
            "Lesson Plan Text",
            "Local URL",
            "Location",
            "Materials",
            "Name",
            "Number of Attachments",
            "Objectives",
            "Occupation",
            "Original Format",
            "Pages",
            "Participants",
            "Physical Dimensions",
            "Place of origin",
            "Producer",
            "Profession",
            "Profile",
            "Publication ID",
            "Publication year",
            "Related objects",
            "Related publications",
            "Short title",
            "Standards",
            "Start date",
            "Status",
            "Subject Line",
            "Text",
            "Time Summary",
            "Title",
            "To",
            "Transcription",
            "URL",
            "King",
            "Month",
            "Type and content",
            "Period",
            "Other markings",
            "Akkadian keywords",
            "General keywords",
            "Alternative label",
            "Archive ID",
            "Concept ID",
            "Hierarchy",
            "Bibliography",
            "Is part of",
            "Is part off",
            "Label",
            "Kinship circle",
            "Page number",
            "Julian date year",
            "Object ID",
            "Occurrence ID",
            "Parent id",
            "Person",
            "Person role",
            "Person code",
            "Person profession",
            "Person status",
            "Place description",
            "Place id",
            "Place name",
            "Place of issue ID",
            "Related object",
            "Role",
            "Volume no.",
            "Orientation",
            "Philological notes",
            "Day remark",
            "King remark",
            "Month remark",
            "dc:title",
            "ID",
            "Itemname",
            "Listid",
            "parent",
            "Parentid",
            "Text number"
        ];
        jQuery.each(blackListElements, function (index, value) {
            jQuery(".advanced-search-element option").filter(function () {
                if (jQuery(this).text() == value)
                {
                    jQuery(this).remove();
                }
            });
        });

        jQuery('#reset_search_advanced').click(function(event) {
            event.preventDefault();
            window.location.href = "<?php echo url('items/search/');?>";
        });

    });
</script>

<?php $test = type_and_content_search();?>
<script>
    jQuery(document).ready(function() {
      var src = <?php echo (json_encode($test));?>

      var stateSel = document.getElementById("stateSel"),
          countySel = document.getElementById("countySel"),
          citySel = document.getElementById("citySel");
      for (var state in src) {
          stateSel.options[stateSel.options.length] = new Option(state, state);
      }
      stateSel.onchange = function () {
          countySel.length = 1; // remove all options bar first
          citySel.length = 1; // remove all options bar first
          if (this.selectedIndex < 1) {
            countySel.options[0].text = "";
            citySel.options[0].text = "";
            return; // done
          }
          //countySel.options[0].text = ""
          for (var county in src[this.value]) {
              countySel.options[countySel.options.length] = new Option(county, county);
              countySel.disabled=false;
          }
          if (countySel.options.length==2) {
              countySel.selectedIndex=1;
              countySel.onchange();
          }
          if (countySel.options.length==1) {
              countySel.disabled=true;
              citySel.disabled=true;
          }
      }
      stateSel.onchange(); // reset in case page is reloaded
      countySel.onchange = function () {
          citySel.length = 1; // remove all options bar first
          if (this.selectedIndex < 1) {
            citySel.options[0].text = "";
            citySel.disabled=true;
            return; // done
          }

          var cities = src[stateSel.value][this.value];
          for (var city in cities) {
              citySel.options[citySel.options.length] = new Option(city, city);
              citySel.disabled=false;
          }
          if (citySel.options.length==2) {
              citySel.selectedIndex=1;
          }
      }
    });
</script>
