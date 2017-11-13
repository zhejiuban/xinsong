@extends('layouts.app')

@section('content')
  a
@endsection

@section('js')
  <script type="text/javascript">
  jQuery(document).ready(function () {
    mAppExtend.notification('asd'
      ,'error');
  });
  </script>
@endsection
