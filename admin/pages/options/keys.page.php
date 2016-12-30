<?php
/* 
 * The MIT License
 *
 * Copyright 2016 kyto.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>

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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $query = 'SELECT * FROM '.DB_DATABASE.'.key_data k';
                                Foreach ($dbp->query($query) as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['key_name'] ?></td>
                                        <td><?php echo ($row['key_type'] == 1 ? 'Private' : 'Public') ?></td>
                                        <td><?php echo ($row['key_mode'] == 1 ? 'Test' : 'Live') ?></td>
                                        <td><?php echo $row['key_data'] ?></td>
                                            <td><?php echo $row['status'] ?></td>
                                                <td><i class=""></i></td>
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