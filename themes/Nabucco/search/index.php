<?php
$pageTitle = __('Search results ') . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();

$session = new Zend_Session_Namespace('pagination_help');
$per_page = $session->per_page;

if(isset($_GET['per_page'])):
  $per_page = $_GET['per_page'];
else:
  $per_page = get_option('public_per_page');
endif;
?>
<div id="primary">
    <h2><?php echo $pageTitle; ?></h2>
    <?php echo search_filters(); ?>
    <?php if ($total_results): ?>
        <?php echo pagination_links(array('per_page'=>$per_page)); ?>
        <table id="search-results">
            <thead>
                <tr>
                    <th><?php echo __('Type'); ?></th>
                    <th><?php echo __('Title'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
                <?php foreach (loop('search_texts') as $searchText): ?>
                    <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
                    <?php $recordType = $searchText['record_type']; ?>
                    <?php set_current_record($recordType, $record); ?>
                    <tr class="<?php echo strtolower($filter->filter($recordType)); ?>">
                        <td>
                            <?php if($searchRecordTypes[$recordType] == 'Item'):
                                echo $record->getItemType()->name;
                            else:
                                echo $searchRecordTypes[$recordType];
                            endif;?>
                        </td>
                        <td>
                            <!--<?php if ($recordImage = record_image($recordType, 'square_thumbnail')): ?>
                                <?php echo link_to($record, 'show', $recordImage, array('class' => 'image')); ?>
                            <?php endif; ?>-->
                            <?php if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])):?>
                                <?php $searchlink = record_url($record, 'show').'?' . $_SERVER['QUERY_STRING'];?>
                                <a href="<?php echo $searchlink; ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
                            <?php else:?>
                                <a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo pagination_links(array('per_page'=>$per_page)); ?>
    <?php else: ?>
        <div id="no-results">
            <p><?php echo __('Your query returned no results.'); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php echo foot(); ?>
