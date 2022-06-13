@php
    $curr_account = $account;
@endphp
<script type="text/javascript">notes = {!! $notes !!};</script>
<div class="modal fade" id="filterPopup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card filter-block">
                <form action="{{ route('transaction_view') }}">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Filter <span class="pull-right"><i class="material-icons">filter_list</i></span></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                              <div class="bmd-form-group">
                                <label for="from">From</label>
                                  <div class='input-group'>
                                      <input type='text' name="from" class="form-control datepicker" id="from" required @if(old('from')) value="{{ old('from') }}" @else value="{{ $from }}" @endif @if(old('all_trans')) disabled="disabled" @endif/>
                                  </div>
                              </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                              <div class="bmd-form-group">
                                <label for="till">To</label>
                                  <div class='input-group'>
                                      <input type='text' name="till" class="form-control datepicker" id="till" required @if(old('till')) value="{{ old('till') }}" @else value="{{ $till }}" @endif @if(old('all_trans')) disabled="disabled" @endif />
                                  </div>
                              </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="all_trans" name="all_trans" @if(old('all_trans')) checked="checked" @endif value="1">All Transactions
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="account">Accounts</label>
                                <div class="accounts multiple">
                                    @foreach($accounts as $acct)
                                    <label class="form-check form-check-inline form-check-label select-value @if(old('active_accounts_only') && !$acct->status) disabled @endif">
                                        <span class="badge" color-code="{{ $acct->color->code }}" style="@if($acct->id == $account->id || (old('aids') && in_array($acct->id,old('aids')))) background-color:@else color:@endif {{ $acct->color->code }};">{{ $acct->name }}</span>
                                        <input type="checkbox" name="aids[]" value="{{ $acct->id }}" status="{{ $acct->status }}" @if(old('active_accounts_only') && !$acct->status) disabled="disabled" @endif @if($acct->id == $account->id || (old('aids') && in_array($acct->id,old('aids')))) checked @endif>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="activeAccountsOnly" name="active_accounts_only" @if(old('active_accounts_only')) checked="checked" @endif value="1">Active Accounts Only
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                    <label class="form-check-label pull-right">
                                        <input class="form-check-input" id="instantFilterBtn" name="instantFilterBtn" @if(old('instantFilterBtn')) checked="checked" @endif type="checkbox">Instant Filter
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 @if(old('instantFilterBtn')) hide @endif" id="filters">
                              <div class="form-group">
                                <label for="filter">Filter</label>
                                  <div class="input-group">
                                    <select class="form-control" name="filter" id="filter" @if(old('instantFilterBtn')) disabled="disabled" @endif>
                                        <option value="">None</option>
                                        @foreach($filters as $filter)
                                        <option value="{{ $filter->id }}" @if($selectedFilter && $selectedFilter->id == $filter->id) selected @endif >{{ $filter->name }}</option>
                                        @endforeach
                                    </select>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="hide" id="instantFilter" @if(old('instantFilterBtn')) style="display: block" @endif>
                        <div class="row">
                            <div class="col-md-12 filter-field">
                                <label for="category">Categories</label>
                                <div class="categories multiple">
                                    @foreach($categories as $category)
                                    <label class="form-check form-check-inline form-check-label select-value">
                                        <span class="badge badge-pill" color-code="{{ $category->color->code }}" style="@if(old('cids') && in_array($category->id,old('cids'))) background-color:@else color:@endif{{ $category->color->code }};">{{ $category->name }}</span>
                                        <input type="checkbox" name="cids[]" value="{{ $category->id }}" @if(old('cids') && in_array($category->id,old('cids'))) checked @endif>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 filter-field">
                                <label for="type">Type</label>
                                <div>
                                    <div class="form-check d-inline-block togglebutton @if(old('notype') || !old('type')) disabled @endif" id="type-toggle">
                                        <label>
                                            <span class="exp">Expense</span>
                                            <input type="checkbox" id="trans-type" data-exp="1" data-inc="2" @if(old('type') == 2) checked="checked" @endif>
                                            <span class="toggle"></span>
                                            <span class="inc">Income</span>
                                        </label>
                                        <input type="hidden" id="type" @if(old('type')) value="{{ old('type') }}" @endif name="type">
                                    </div>
                                    <div class="form-check d-inline-block" style="margin-left: 20px; margin-top: 0;">
                                        <label class="form-check-label">
                                            <input class="form-check-input" id="no-type" name="notype" @if(old('notype') || !old('type')) checked="checked" @endif type="checkbox"> Show Both
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 filter-field">
                                <label for="category">Payment Modes</label>
                                <div class="form-check" style="margin: 0">
                                    @foreach($paymentModes as $paymentMode)
                                    <label class="form-check form-check-inline form-check-label">
                                        <input class="form-check-input" type="checkbox" name="mods[]" value="{{ $paymentMode->id }}" @if(old('mods') && in_array($paymentMode->id,old('mods'))) checked @endif>{{ $paymentMode->name }}
                                        <span class="form-check-sign"><span class="check"></span></span>
                                    </label>
                                    @endforeach
                                </div>
                                <small><i>Select or un-select all will show the transactions of all payment modes.</i></small>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 filter-field">
                                <div class="input-group no-border">
                                    <input type="text" name="search" value="{{ old('search') }}" class="form-control autocomplete" placeholder="Search">
                                    <span class="clear search"><i class="material-icons">close</i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
                                <button type="button" class="btn btn-primary btn-round btn-fab pull-right" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
