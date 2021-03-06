@extends('../nav')

@section('main')
<div class="nav-pad"></div>

<section>
    <div class="column">
        <p></p>
        <div class="container col-4 col-md-6 col-sm-10 col-xs-12 p-centered">
            <div class="panel">
                <div class="panel-header">
                    <a href="/expos" class="text-dark"><i class="fa fa-map-marker" aria-hidden="true"></i> 会展发布</a>
                </div>
            <div class="panel-body">
                <form method="POST" action="/expos/store">
                    @csrf

                    <div class="form-group @error('title') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">标题</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input" type="text" name="title" minlength="4" maxlength="16" placeholder="标题" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('addr') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">地址</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input" type="text" name="addr" minlength="6" maxlength="100" placeholder="地址" value="{{ old('addr') }}" required>
                        @error('addr')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('begin') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">开始时间</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input"type="datetime-local" name="begin" value="{{ old('begin') ? old('begin') : "2020-06-01T12:00" }}" placeholder="开始日期" required>
                        @error('begin')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('end') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">结束时间</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input" type="datetime-local" name="end" value="{{ old('end') ? old('end') : "2020-06-01T12:00" }}"  placeholder="结束日期" required>
                        @error('end')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('ready') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">开放购票时间</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input"type="datetime-local" name="ready" value="{{ old('ready') ? old('ready') : "2020-06-01T12:00" }}" placeholder="开放购票时间" required>
                        @error('ready')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('price') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">票价</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input" name="price" type="number" min="0" max="1000" step="0.01" value="{{ old('price') }}" placeholder="票价" required>
                        @error('price')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('limit') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">会展容量</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input" name="limit" type="number" min="100" max="100000" value="{{ old('limit') }}" placeholder="会展容量" required>
                        @error('limit')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('manager') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">负责人</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input" type="text" name="manager" minlength="2" maxlength="110" value="{{ old('manager') }}" placeholder="用户名/手机号,多人以逗号隔开">
                        @error('manager')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('checker') has-error @enderror">
                        <div class="col-3 col-sm-12">
                        <label class="form-label" for="input-example-1">检票员</label>
                        </div>
                        <div class="col-9 col-sm-12">
                        <input class="form-input" type="text" name="checker" minlength="2" maxlength="110" value="{{ old('checker') }}"  placeholder="用户名/手机号,多人以逗号隔开">
                        @error('checker')
                            <p class="form-input-hint">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group @error('open') has-error @enderror">
                        <label class="form-switch">
                        <input type="checkbox" name="on" value="{{ old('on') }}" >
                        <i class="form-icon"></i> 上线并开放购票
                        </label>
                    </div>
                    <p></p>
                    <button class="btn btn-primary btn-block mt-2" type="submit">发布</button>
                </form>
                <div class="panel-footer">

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
