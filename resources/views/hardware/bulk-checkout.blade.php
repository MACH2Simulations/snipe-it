@extends('layouts/default')

{{-- Page title --}}
@section('title')
     {{ trans('admin/hardware/general.bulk_checkout') }}
@parent
@stop

{{-- Page content --}}
@section('content')

<style>
  .input-group {
    padding-left: 0px !important;
  }
</style>
<!-- webnfc-->
<div>
<script>
  document.querySelector("#scanButton3").onclick = async () => {
    const ndef = new NDEFReader();
    await ndef.scan();
  ndef.addEventListener("reading", ({ message, serialNumber }) => {
    //window.alert(`> Serial Number: ${serialNumber}`);
    //window.alert(`> Records: (${message.records.length})`);
    for (const record of message.records) {
    console.log("Record type:  " + record.recordType);
    console.log("MIME type:    " + record.mediaType);
    console.log("Record id:    " + record.id);
    switch (record.recordType) {
      case "text":
      const textDecoder = new TextDecoder(record.encoding);
      const decodedData = textDecoder.decode(record.data);
      window.alert(decodedData);
      document.querySelector('select2-search__field').value = decodedData;
      case "url":
        break;
      default:
        break;
    }
    ndef.addEventListener("readingerror", () => {
      window.alert("Argh! Cannot read data from the NFC tag. Try another one? or Try again");
    });
  }
});
};
  </script>
<button type="Scan" id="scanButton3" class="btn btn-success"><i class="fas fa-check icon-white" aria-hidden="true"></i> Scan1</button>   <!-- webnfc-->    
 </div> 
  <!--end webnfc-->

<div class="row">
  <!-- left column -->
  <div class="col-md-7">              
      <div class="box-header with-border">
        <h2 class="box-title"> {{ trans('admin/hardware/form.tag') }} </h2>
        
        <!--WEBNFC SCAN BUTTON-->
              <button type="Scan" id="scanButton" class="btn btn-success pull-right"><i class="fas fa-check icon-white" aria-hidden="true"></i> Scan</button>
    <div class="box box-default">
      </div>
      <div class="box-body">
        <form class="form-horizontal" method="post" action="" autocomplete="off">
          {{ csrf_field() }}

            @include ('partials.forms.edit.asset-select', [
           'translated_name' => trans('general.assets'),
           'fieldname' => 'selected_assets[]',
           'multiple' => true,
           'required' => true,
           'asset_status_type' => 'RTD',
           'select_id' => 'assigned_assets_select',
           'asset_selector_div_id' => 'assets_to_checkout_div',
           'asset_ids' => old('selected_assets')
         ])



            <!-- Checkout selector -->
          @include ('partials.forms.checkout-selector', ['user_select' => 'true','asset_select' => 'true', 'location_select' => 'true'])

          @include ('partials.forms.edit.user-select', ['translated_name' => trans('general.user'), 'fieldname' => 'assigned_user'])
            @include ('partials.forms.edit.asset-select', ['translated_name' => trans('general.asset'), 'asset_selector_div_id' => 'assigned_asset', 'fieldname' => 'assigned_asset', 'unselect' => 'true', 'style' => 'display:none;'])
          @include ('partials.forms.edit.location-select', ['translated_name' => trans('general.location'), 'fieldname' => 'assigned_location', 'style' => 'display:none;'])

          <!-- Checkout/Checkin Date -->
              <div class="form-group {{ $errors->has('checkout_at') ? 'error' : '' }}">
                  <label for="checkout_at" class="col-sm-3 control-label">
                      {{ trans('admin/hardware/form.checkout_date') }}
                  </label>
                  <div class="col-md-8">
                      <div class="input-group date col-md-5" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d" data-date-clear-btn="true">
                          <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="checkout_at" id="checkout_at" value="{{ old('checkout_at') }}">
                          <span class="input-group-addon"><x-icon type="calendar" /></span>
                      </div>
                      {!! $errors->first('checkout_at', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                  </div>
              </div>

              <!-- Expected Checkin Date -->
              <div class="form-group {{ $errors->has('expected_checkin') ? 'error' : '' }}">
                  <label for="expected_checkin" class="col-sm-3 control-label">
                      {{ trans('admin/hardware/form.expected_checkin') }}
                  </label>
                  <div class="col-md-8">
                      <div class="input-group date col-md-5" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-start-date="0d" data-date-clear-btn="true">
                          <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="expected_checkin" id="expected_checkin" value="{{ old('expected_checkin') }}">
                          <span class="input-group-addon"><x-icon type="calendar" /></span>
                      </div>
                      {!! $errors->first('expected_checkin', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                  </div>
              </div>


          <!-- Note -->
          <div class="form-group {{ $errors->has('note') ? 'error' : '' }}">
              <label for="note" class="col-sm-3 control-label">
                  {{ trans('general.notes') }}
              </label>
            <div class="col-md-8">
              <textarea class="col-md-6 form-control" id="note" name="note">{{ old('note') }}</textarea>
              {!! $errors->first('note', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
          </div>



      </div> <!--./box-body-->
      <div class="box-footer">
        <a class="btn btn-link" href="{{ URL::previous() }}"> {{ trans('button.cancel') }}</a>
        <button type="submit" class="btn btn-primary pull-right"><x-icon type="checkmark" /> {{ trans('general.checkout') }}</button>
      </div>
    </div>
      </form>
  </div> <!--/.col-md-7-->

  <!-- right column -->
  <div class="col-md-5" id="current_assets_box" style="display:none;">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h2 class="box-title">{{ trans('admin/users/general.current_assets') }}</h2>
      </div>
      <div class="box-body">
        <div id="current_assets_content">
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('moar_scripts')
@include('partials/assets-assigned')
<script nonce="{{ csrf_token() }}">
    $(function () {
        //if there's already a user selected, make sure their checked-out assets show up
        // (if there isn't one, it won't do anything)
        $('#assigned_user').change();
    });
</script>

@stop
