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
        <li><a href="<?php echo base_url('/po-page');?>">PO List</a></li>
        <li class="active">PO<?php echo $po->po_id ?></li>
    </ol>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End breadcrumb-->
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <!-- Basic Data Tables -->
        <!--===================================================-->
        <div class="panel" style="height: 1500px;">



            <div class="panel-heading">
                <div class="form-group col-md-10">
                    <h3 class="panel-title"><b>PO<?php echo $po->po_id; ?></b></h3>
                </div>
                <div class="form-group col-md-2" align="right">
                    <br/>
                    <button class="btn btn-default" onclick="go_to_po_list()"><i class="fa fa-reply"></i> &nbsp;Back to PO list</button>
                </div>
            </div>

            <div class="form-body">
            <div class="form-group">
                                    
                <label class="control-label col-md-3">Supplier: <h4><?php echo $po->supplier_id; ?></h4></label>

                <label class="control-label col-md-3">Date: <h4><?php echo $po->date; ?></h4></label>

                <label class="control-label col-md-3">Created by: <h4><?php echo $this->users->get_username($po->user_id); // get name instead of id ?></h4></label>

                <label class="control-label col-md-3">Status: <h4><?php echo $po->status; // get name instead of id ?></h4></label>                            
                
            </div>   
            </div>

            <div class="control-label col-md-12">
            
            <hr style="background-color: #ccccff; height: 3px;">

            <div class="panel-heading">
                <div class="form-group col-md-6">
                    <h3 class="panel-title">PO Details Table</h3>
                </div>
                <div class="form-group col-md-6" align="right">
                    <button class="btn btn-danger" style="font-size: 15px;"  onclick="complete_po()"><i class="fa fa-cog"></i> &nbsp;Cancel PO</button>
                    <button class="btn btn-primary" style="font-size: 15px;"  onclick="cancel_po()"><i class="fa fa-cog"></i> &nbsp;Complete PO</button>
                </div>
            </div>
            <hr>
            </div>


            
            <div class="panel-body">
                <div class="form-group col-md-4">
                    <button class="btn btn-success" onclick="add_po_detail()"><i class="fa fa-plus-square"></i> &nbsp;Add new PO item</button>
                    <button class="btn btn-default" onclick="reload_table()"><i class="fa fa-refresh"></i> &nbsp;Reload</button>
                </div>
                <form action="#" id="form-po-set" class="form-horizontal col-md-6">
                    <input type="hidden" value=<?php echo $po->po_id ?> name="po_id" id="po_id"/>
                    <div class="form-body">
                        <div class="form-group col-md-8">
                            <div>
                                <select name="supplier_id" id="supplier_id_final" class="form-control" style="font-size: 15px;">
                                    <option value="">--Select Supplier--</option>
                                    <?php 
                                        foreach($suppliers as $row)
                                        { 
                                            if ($po->supplier_id == $row->supplier_id)
                                            {
                                                echo '<option value="'.$row->supplier_id.'" selected>SU'.$row->supplier_id.': '.$row->name.'</option>';
                                            }
                                            else
                                            {
                                                echo '<option value="'.$row->supplier_id.'">SU'.$row->supplier_id.': '.$row->name.'</option>';
                                            }
                                            
                                        }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div>
                                <input name="date" id="po_date_final" class="form-control" type="date" value=<?php echo $po->date ?>>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
                <br><br>
                <table id="po-details-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Prod ID</th>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>ArrivedQty</th>
                            <th style="width:60px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!--===================================================-->
        <!-- End Striped Table -->
        <!-- <span>Legend: [ &nbsp; <i style = "color: #99ff99;" class="fa fa-square"></i> - Today &nbsp; | &nbsp; <i style = "color: #cccccc;" class="fa fa-square"></i> - Ended &nbsp; | &nbsp; <i style = "color: #ffffff;" class="fa fa-square"></i> - Incoming &nbsp; ]</span> -->
    </div>
    <!--===================================================-->
    <!--End page content-->
</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">PO Item Edit Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value=<?php echo $po->po_id ?> name="po_id"/>
                    <input type="hidden" value="" name="num"/>
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Product:</label>
                            <div class="col-md-9">
                                <select name="prod_id" id="prod_id" class="form-control" disabled>
                                    <option value="">--Select Product--</option>
                                    <?php 
                                        foreach($products as $row)
                                        { 
                                            echo '<option value="'.$row->prod_id.'">P'.$row->prod_id.': '.$row->name.'</option>';
                                        }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity:</label>
                            <div class="col-md-9">
                                <input name="unit_qty" placeholder="Enter unit quantity" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Arrived quantity:</label>
                            <div class="col-md-9">
                                <input name="arrived_qty" placeholder="Enter arrived quantity" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><i class="fa fa-floppy-o"></i> &nbsp;Save</button>

                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> &nbsp;Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->