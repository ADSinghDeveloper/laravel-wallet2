<div class="modal fade" id="delallPopup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card filter-block">
                <form action="{{ route('del_all') }}" method="post">
                    <div class="card-header card-header-danger">
                        <h4 class="card-title">Delete All User Data <span class="pull-right"><i class="material-icons">delete</i></span></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 filter-field">
                                <div class="input-group no-border">
                                    <input type="password" tabindex="1" required name="confirm_del" class="form-control" placeholder="Enter password to confirm delete all user dala">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary"><i class="material-icons">delete</i></button>
                                <button type="button" class="btn btn-primary btn-round btn-fab pull-right" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>