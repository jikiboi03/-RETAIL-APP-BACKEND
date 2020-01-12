            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow"><?php echo $title; ?></h1>

                    <!--Searchbox-->
                    <!-- <div class="searchbox">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..">
                            <span class="input-group-btn">
                                <button class="text-muted" type="button"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div> -->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->

                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
                    <li class="active">Stock List</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <!-- Basic Data Tables -->
                    <!--===================================================-->
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Stocks Information Table</h3>
                        </div>
                        <div class="panel-body">
                            <button class="btn btn-success" onclick="create_po()"><i class="fa fa-plus-square"></i> &nbsp;Create PO</button>
                            <button class="btn btn-default" onclick="reload_table()"><i class="fa fa-refresh"></i> &nbsp;Reload</button>
                            <br><br>
                            <table id="items-table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 14px;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="width:60px;">Prod ID</th>
                                        <th>Name</th>
                                        <th>Short Name</th>
                                        <th>Stock(pcs)</th>
                                        <th>S.In</th>
                                        <th>S.Out</th>
                                        <th>ReorderPt</th>
                                        <th class="min-desktop">Encoded</th>
                                        <th style="width:10px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--===================================================-->
                    <!-- End Striped Table -->
                    <div class="col-md-8">
                        <span>Legend: [ &nbsp; 
                        <i style = "color: #FFFF99;" class="fa fa-square"></i><i style = "color: #FFFF66;" class="fa fa-square"></i> - Warning level &nbsp; 
                        | &nbsp; 
                        <i style = "color: #ffd8b4;" class="fa fa-square"></i><i style = "color: #ffe5cd;" class="fa fa-square"></i> - Critical level &nbsp; ]</span>
                    </div>
                    <!-- <span>Legend: [ &nbsp; <i style = "color: #99ff99;" class="fa fa-square"></i> - Today &nbsp; | &nbsp; <i style = "color: #cccccc;" class="fa fa-square"></i> - Ended &nbsp; | &nbsp; <i style = "color: #ffffff;" class="fa fa-square"></i> - Incoming &nbsp; ]</span> -->
                </div>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

            <form action="#" id="form" class="form-horizontal">
                <input type="hidden" value="" name="selected" id="selected"/>
            </form>