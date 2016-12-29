<div id="page-wrapper">
    <div class="row">
        <br/>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Stripe Keys
                    <div class="pull-right">

                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Key Name</th>
                                    <th>Key Type</th>
                                    <th>Key Mode</th>
                                    <th>Key Code</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                                
                    <?php
                    $query = 'SELECT * FROM '.DB_DATABASE.'.key_data k';
                    FOREACH ($dbp->query($query) as $row){ ?>
                            <tr>
                                <td><?php echo $row['key_name']?></td>
                                <td><?php echo ($row['key_type']==1?'Private':'Public')?></td>
                                <td><?php echo ($row['key_mode']==1?'Test':'Live')?></td>
                                <td><?php echo $row['key_data']?></td>
                                <td><?php echo $row['status']?></td>
                            </tr>
                    <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
            
        <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> New Key
                    <div class="pull-right">

                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><label for="key_name">Key Name</th>
                                    <th><label for="key_type">Key Type</th>
                                    <th><label for="key_mode">Key Mode</th>
                                    <th><label for="key_data">Key Code</th>
                                    <th><label for="status">Status</label></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" id="key_name"></td>
                                <td><select id="key_type"><option value="1">Private</option><option value="2">Public</option></select></td>
                                <td><select id="key_mode"><option value="1">Private</option><option value="2">Public</option></select></td>
                                <td><input type="text" id="key_data"></td>
                                <td><select id="status"><option value="1">Enabled</option><option value="0">Disabled</option></select></td>
                                <td><button id="createKey" class="btn btn-success btn-block">Create Key</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
    </div>
</div>