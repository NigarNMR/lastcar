<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">

            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-order-status">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>                 
                                    <td class="text-left"><?php echo $column_name; ?></td>                  
                                    <td class="text-right">Должно присутствовать</td>
                                    <td class="text-right">Может присутствовать</td>
                                    <td class="text-right"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($order_statuses) { ?>
                                <?php foreach ($order_statuses as $status_workflow) { ?>
                                <tr>
                                    <td class="text-left"><?php echo $status_workflow['name']; ?></td>
                                    <td class="text-center">
                                        <?php if(isset($status_workflow['workflow'][1])){?>
                                            <?php foreach ($status_workflow['workflow'][1] as $status) { ?>
                                                <?php echo $status['name'].'<br>';?>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isset($status_workflow['workflow'][2])){?>
                                            <?php foreach ($status_workflow['workflow'][2] as $status) { ?>
                                                <?php echo $status['name'].'<br>';?>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <!--
                                    <td class="text-center">
                                        <?php if(isset($status_workflow['workflow'][3])){?>
                                            <?php foreach ($status_workflow['workflow'][3] as $status) { ?>
                                                <?php echo $status['name'].'<br>';?>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    -->
                                    <td class="text-right"><a href="<?php echo $status_workflow['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>                                
                                <tr>
                                    <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="row">
                </div>  
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?> 