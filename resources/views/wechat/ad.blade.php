@extends('../nav')

@section('main')
<div class="container col-4 col-md-6 col-sm-10 col-xs-12 p-centered">
    <div class="nav-pad"></div>
    <input type="radio" id="tab1" name="tabs" class="tab-locator" hidden checked />
    <input type="radio" id="tab2" name="tabs" class="tab-locator" hidden />
    <input type="radio" id="tab3" name="tabs" class="tab-locator" hidden />

    <div class="panel">
        <div class="panel-header text-center">
        <div class="panel-title h5 mt-10">{{ face(Auth::user())->name }}</div>
        </div>
        <nav class="panel-nav">
            <ul class="tab tab-block" data-tabs="main_tab">
                @can('customer-qrcode', Auth::user())
                <li class="tab-item active"><a href="#">客户</a></li>
                @endcan
                @can('supplier-qrcode', Auth::user())
                <li class="tab-item"><a href="#">商户</a></li>
                @endcan
                @can('partner-qrcode', Auth::user())
                <li class="tab-item"><a href="#">合作</a></li>
                @endcan
            </ul>
        </nav>
        <div class="panel-body">
            <ul data-tabs-content="main_tab">

                @can('customer-qrcode', Auth::user())
                <li>
                    <div class="text-center">
                    {!! QrCode::size(230)->color(60,68,82)->margin(0)->generate($urls['customer']); !!}
                    </div>
                </li>
                @endcan
                @can('supplier-qrcode', Auth::user())
                <li>
                    <div class="text-center">
                    {!! QrCode::size(230)->color(239,83,80)->margin(0)->generate($urls['supplier']); !!}
                    </div>
                </li>
                @endcan
                @can('partner-qrcode', Auth::user())
                <li>
                    <div class="text-center">
                    {!! QrCode::size(230)->color(49,154,201)->margin(0)->generate($urls['partner']); !!}
                    </div>
                </li>
                @endcan
            </ul>
        </div>
        <div class="panel-footer">
        <button class="btn btn-primary btn-block" onclick="javascript:re()">刷新</button>
        </div>
    </div>
</div>
<script>
    function re()
    {
        window.location.replace(path);
    }
</script>
@endsection
