<?php echo head(array('title' => metadata($item, array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>
<div id="primary">

    <h1><span class="title-type"><?php echo $type = $item->getItemType()->name;?></span><?php echo metadata($item, array('Dublin Core', 'Title')); ?></h1>
    <?php
    $item = get_current_record('Item');
    $relations = libis_get_relations($item, 'subject');
    $object_relations = libis_get_relations($item, 'object');

    if ($type = 'News'):
        echo "<div class='date'>" . metadata('item', array('Item Type Metadata', 'Start date')) . "</div>";
    endif;
    ?>
    <!-- TABLET -->
    <?php if ($item->getItemType()->name == 'Tablet'): ?>
        <div class="item hentry">
            <div class="show-section">
                <div class="show-block">
                    <table>
                    <tr>
                      <th><h3>NaBuCCo No.</h3></th>
                      <th><h3>Museum No.</h3></th>
                      <th><h3>CDLI No.</h3></th>
                      <th><h3>Duplicate</h3></th>
                    </tr>
                    <tr>
                        <td><?php if ($text = metadata($item, array('Item Type Metadata', 'Object ID'))): ?>
                            <?php echo $text; ?>
                        <?php endif; ?></td>

                        <td><?php if ($text = metadata($item, array('Item Type Metadata', 'Museum No.'))): ?>
                            <?php echo $text; ?>
                        <?php endif; ?></td>

                        <td><?php if ($text = metadata($item, array('Item Type Metadata', 'CDLI No.'))): ?>
                            <?php if ($link = metadata($item, array('Item Type Metadata', 'CDLI reference'))): ?>
                              <a href="<?php echo $link; ?>"><?php echo $text; ?></a>
                            <?php else: ?>
                              <?php echo $text; ?>
                            <?php endif; ?>

                        <?php endif; ?></td>

                        <td>
                          <?php if (isset($object_relations['tablets'])): ?>
                            <?php
                            foreach ($object_relations['tablets'] as $tablet):
                                echo link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title')));
                            endforeach;
                            ?>
                          <?php elseif ($text = metadata($item, array('Item Type Metadata', 'Duplicate'))): ?>
                              <?php echo $text; ?>
                          <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><h3>Period</h3></th>
                        <th><h3>Babylonian date</h3></th>
                        <th><h3>Julian date</h3></th>
                        <th><h3>Join</h3></th>
                    </tr>
                    <tr>
                        <td><?php if ($text = metadata($item, array('Item Type Metadata', 'Period'),array('all'=>'true','delimiter'=>', '))): ?>
                           <?php echo $text; ?>
                        <?php endif; ?></td>
                        <?php
                        $day = metadata($item, array('Item Type Metadata', 'Day (Babylonian)'));
                        $remark_day = metadata($item, array('Item Type Metadata', 'Day remark'));
                        $month = metadata($item, array('Item Type Metadata', 'Month'));
                        $remark_month = metadata($item, array('Item Type Metadata', 'Month remark'));
                        $year = metadata($item, array('Item Type Metadata', 'Year'));
                        $remark_year = metadata($item, array('Item Type Metadata', 'Year remark'));
                        $king = metadata($item, array('Item Type Metadata', 'King'));
                        $remark_king = metadata($item, array('Item Type Metadata', 'King remark'));

                        if ($day || $remark_day):
                            $day = libis_get_date($day, $remark_day);
                        endif;
                        ?>
                        <?php
                        if ($month || $remark_month):
                            $month = libis_get_date($month, $remark_month);
                        endif;
                        ?>
                        <?php
                        if ($year || $remark_year):
                            $year = libis_get_date($year, $remark_year);
                        endif;
                        ?>
                        <?php
                        if ($king || $remark_king):
                            $king = libis_get_date($king, $remark_king);
                        endif;
                        ?>
                        <td><?php echo $day . "." . $month . "." . $year . " " . $king; ?></td>
                        <td><?php if ($text = metadata($item, array('Item Type Metadata', 'Julian date'))): ?>
                            <?php echo $text; ?>
                            <?php endif; ?></td>
                        <td>
                          <?php if ($text = metadata($item, array('Item Type Metadata', 'Join'),array('all'=>'true','delimiter'=>', '))): ?>
                             <?php echo $text; ?>
                          <?php endif; ?>
                        </td>
                    </tr>
                  </table>

                  <?php if ($pub = metadata($item, array('Item Type Metadata', 'Publication'))): ?>
                      <div class="item-meta">
                          <p><span class="show-title">Publication</span>
                          <?php
                          if ($text = metadata($item, array('Item Type Metadata', 'Text number'))):
                              $pub .= " " . $text;
                          endif;
                          if ($text = metadata($item, array('Item Type Metadata', 'Page number'))):
                              $pub .= ", " . $text;
                          endif;

                          if (isset($object_relations['bibliographies'])):
                              echo link_to($object_relations['bibliographies'][0], null,$pub);
                          else:
                              echo $pub;
                          endif;
                          ?>
                          </p>
                      </div>
                  <?php endif; ?>

                  <?php if ($biblio = metadata($item, array('Item Type Metadata', 'Bibliography (free text)'))): ?>
                      <div class="item-meta">
                          <p><span class="show-title">Bibliography (free text)</span>
                              <?php echo $biblio;?>
                          </p>
                      </div>
                  <?php endif; ?>

                  <div class="show-interal-block">
                      <?php if (isset($object_relations['places'])): ?>
                      <div class="item-meta">
                          <p><span class="show-title">Place of issue</span>
                              <?php
                              foreach ($object_relations['places'] as $place):
                                  echo link_to($place, null, metadata($place, array('Dublin Core', 'Title')));
                              endforeach;
                              ?></p>
                      </div>
                      <?php elseif ($text = metadata($item, array('Item Type Metadata', 'Place of issue'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <p><span class="show-title">Place of issue</span>
                            <?php echo $text; ?>
                      </div>
                      <?php endif; ?>
                      <?php if (isset($object_relations['archives'])): ?>
                          <div class="item-meta">
                              <p><span class="show-title">Archive</span>
                              <?php
                              foreach ($object_relations['archives'] as $archive):
                                  echo link_to($archive, null, metadata($archive, array('Dublin Core', 'Title')));
                              endforeach;
                              ?></p>
                          </div>
                      <?php elseif ($text = metadata($item, array('Item Type Metadata', 'Archive'),array('all'=>'true','delimiter'=>', '))): ?>
                          <div class="item-meta">
                              <p><span class="show-title">Archive</span>
                              <?php echo $text; ?></p>
                          </div>
                      <?php endif; ?>

                      <?php if ($text = metadata($item, array('Item Type Metadata', 'Type and content'),array('all'=>'true','delimiter'=>', '))): ?>
                          <div class="item-meta">
                              <p><span class="show-title">Type and content</span>
                                  <?php echo $text; ?></p>
                          </div>
                      <?php endif; ?>
                      <?php if ($text = metadata($item, array('Item Type Metadata', 'Items and quantifiable data'),array('all'=>'true','delimiter'=>'; '))): ?>
                        <div class="item-meta">
                            <p><span class="show-title">Items and quantifiable data</span>
                            <?php echo $text; ?></p>
                        </div>
                      <?php endif; ?>
                      <!--<?php if ($text = metadata($item, array('Item Type Metadata', 'Other markings'),array('all'=>'true','delimiter'=>', '))): ?>
                          <div class="item-meta">
                              <p><span class="show-title">Other Markings</span>
                                  <?php echo $text; ?></p>
                          </div>
                      <?php endif; ?>-->
                </div>
                <div class="show-block">
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Paraphrase'),array('all'=>'true','delimiter'=>', '))): ?>
                        <div class="item-meta">
                            <h3>Paraphrase</h3>
                            <p><?php echo $text; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Imported'),array('all'=>'true','delimiter'=>', '))): ?>
                        <div align=right class="item-meta">
                            <h3>Imported</h3>
                            <p><?php echo $text; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($relations['people'])): ?>
                    <table>
                        <tr>
                            <th><h3>Persons</h3></th>
                            <th><h3>Role</h3></th>
                            <th><h3>Profession</h3></th>
                            <th><h3>Status</h3></th>
                        </tr>
                        <?php
                        foreach ($relations['people'] as $person):
                           echo "<tr>" . libis_print_person($person, $item) . "</tr>";
                        endforeach;
                        ?>
                    </table>
                    <?php endif; ?>
                </div>

            <p><a class="toggle-more" href="#">+ Show more</a></p>
            <div class="more-info">
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Babylonian date Remark'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>Babylonian date remark</h3>
                          <p><?php echo $text; ?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Julian date Remark'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>Julian date remark</h3>
                          <p><?php echo $text; ?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Seal'))): ?>
                      <div class="item-meta">
                          <h3>Seal</h3>
                          <p><?php echo $text;?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Transliteration'))): ?>
                      <div class="item-meta">
                          <h3>Transliteration</h3>
                          <p><?php echo $text;?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Other markings'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>Other markings</h3>
                          <p><?php echo $text; ?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Other markings Description'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>Other markings Description</h3>
                          <p><?php echo $text; ?></p>
                      </div>
                  <?php endif; ?>

                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Photo'))): ?>
                      <div class="item-meta">
                          <h3>Photo</h3>
                          <div class="tablet-foto">
                              <img src="<?php echo $text;?>">
                              <p><a href="<?php echo $text;?>"><?php echo $text;?></a></p
                          </div>
                      </div>
                  <?php endif; ?>

                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Akkadian keywords'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>Akkadian keywords</h3>
                          <p><?php echo $text; ?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'General keywords'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>General keywords</h3>
                          <p><?php echo $text; ?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Orientation'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>Orientation</h3>
                          <p><?php echo $text; ?></p>
                      </div>
                  <?php endif; ?>
                  <?php if ($text = metadata($item, array('Item Type Metadata', 'Philological notes'),array('all'=>'true','delimiter'=>', '))): ?>
                      <div class="item-meta">
                          <h3>Philological notes</h3>
                          <p><?php echo $text; ?></p>
                          <?php if ($text = metadata($item, array('Item Type Metadata', 'Philological notes Remark'),array('all'=>'true','delimiter'=>', '))): ?>
                            <h4>Remark</h4>
                            <p><?php echo $text; ?></p>
                          <?php endif; ?>
                      </div>
                  <?php endif; ?>
                </div>
            </div>
          </div>

        <?php endif; ?>
        <!-- PEOPLE -->
        <?php if ($item->getItemType()->name == 'People'): ?>
        <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Entity ID'))): ?>
                <div class="item-meta">
                    <h3>Entity ID</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Gender'))): ?>
                <div class="item-meta">
                    <h3>Gender</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Name'))): ?>
                <div class="item-meta">
                    <h3>Name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if (isset($relations['places'])): ?>
                <div class="item-meta">
                    <h3>Place of origin</h3>
                        <p><ul><?php
                        foreach ($relations['places'] as $place):
                            echo "<li>".link_to($place, null, metadata($place, array('Dublin Core', 'Title')))."</li>";
                        endforeach;
                        ?></ul></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Kinship circle'))): ?>
                <div class="item-meta">
                    <h3>Kinship circle</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Family'))): ?>
                <div class="item-meta">
                    <h3>Family</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Profile'))): ?>
                <div class="item-meta">
                    <h3>Profile</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <h3>Related objects</h3>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <!-- Bibliography -->
        <?php if ($item->getItemType()->name == 'Bibliography'): ?>
            <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Short title'))): ?>
                <div class="item-meta">
                    <h3>Short title</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Author'),array('all'=>'true','delimiter'=>'<br>'))): ?>
                <div class="item-meta">
                    <h3>Author</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Publication year'))): ?>
                <div class="item-meta">
                    <h3>Year</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Journal'))): ?>
                <div class="item-meta">
                    <h3>Journal</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Volume no.'))): ?>
                <div class="item-meta">
                    <h3>Volume no.</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Book'))): ?>
                <div class="item-meta">
                    <h3>Book</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Pages'))): ?>
                <div class="item-meta">
                    <h3>Pages</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Editor'),array('all'=>'true','delimiter'=>'<br>'))): ?>
                <div class="item-meta">
                    <h3>Editor</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if (isset($relations['bibliographies'])): ?>
                <div class="item-meta">
                    <h3>Related publications</h3>
                    <ul>
                    <?php
                    foreach ($relations['bibliographies'] as $bibliography):
                        echo "<li>" . link_to($bibliography, null, metadata($bibliography, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;

                    ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <h3>Related objects</h3>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul>
                </div>
            <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Archive -->
        <?php if ($item->getItemType()->name == 'Archive'): ?>
            <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Archive name'))): ?>
                <div class="item-meta">
                    <h3>Archive name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Alternative name'))): ?>
                <div class="item-meta">
                    <h3>Alternative name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <p><span class="show-title">Related objects</span>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul></p>
                </div>
            <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- PLACES -->
        <?php if ($item->getItemType()->name == 'Place'): ?>
            <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Place name'))): ?>
                <div class="item-meta">
                    <h3>Name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Place description'))): ?>
                <div class="item-meta">
                    <h3>Place description</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <p><span class="show-title">Related objects</span>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul></p>
                </div>
            <?php endif; ?>
                 </div>
        <?php endif; ?>

        <!-- GLOSSARY -->
        <?php if ($item->getItemType()->name == 'Glossary'): ?>
            <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Description'))): ?>
                <div class="item-meta">
                    <h3>Description</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <p><span class="show-title">Related objects</span>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul></p>
                </div>
            <?php endif; ?>
                 </div>
        <?php endif; ?>
            <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
       <!-- end class="item hentry" -->
                    <!--  The following function prints all the the metadata associated with an item: Dublin Core, extra element sets, etc. See http://omeka.org/codex or the examples on items/browse for information on how to print only select metadata fields. -->
        <?php //echo all_element_texts($item);  ?>
        <!-- The following returns all of the files associated with an item. -->
        <?php if (metadata($item, 'has files')): ?>
            <div id="itemfiles" class="element">
            <?php echo files_for_item(); ?>
            </div>
        <?php endif; ?>
        <p><?php echo metadata($item, array('Dublin Core', 'Description')); ?></p>
        <!-- The following prints a list of all tags associated with the item -->
        <?php if (metadata($item, 'has tags')): ?>
            <div id="item-tags" class="element">
                <div class="element-text tags"><?php echo tag_string('item'); ?></div>
            </div>
        <?php endif; ?>

        <ul class="item-pagination navigation">
            <?php custom_paging(); ?>
        </ul>
    </div><!-- end primary -->
    <script>
    jQuery( document ).ready(function() {
      if (jQuery(".more-info").find(".item-meta").length < 1){
          jQuery( ".toggle-more" ).css("display","none");
      }
      jQuery( ".toggle-more" ).click(function(event) {
        event.preventDefault();
        if(jQuery( ".toggle-more" ).text()=="+ Show more"){
          jQuery( ".toggle-more" ).text("- Show less");
        }else{
          jQuery( ".toggle-more" ).text("+ Show more");
        }
        jQuery( ".more-info" ).toggle(function() {
        });
      });
    });
    </script>
<?php echo foot(); ?>
