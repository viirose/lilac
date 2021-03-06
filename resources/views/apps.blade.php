@extends('nav')

@section('main')

<div class="nav-pad"></div>
<p></p>
    <div class="container col-sm-8 col-xs-12 p-centered">

        <div class="columns col-gapless">
            <div class="column col-xs-4 text-center mt-2">
                <a href="/gift" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/gift.svg') }}" alt="...">
                </figure>
                <p>抽奖</p>
                </a>
            </div>

            <div class="column col-xs-4 text-center mt-2">
                <a href="/tickets" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/ticket.svg') }}" alt="...">
                </figure>
                <p>票</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/expos" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/expo.svg') }}" alt="...">
                </figure>
                <p>会展</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/customers" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/customer.svg') }}" alt="...">
                </figure>
                <p>客户</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/suppliers" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/supplier.svg') }}" alt="...">
                </figure>
                <p>供应商</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/partners" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/partner.svg') }}" alt="...">
                </figure>
                <p>合作方</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/users" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/users.svg') }}" alt="...">
                </figure>
                <p>员工</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/tasks" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/task.svg') }}" alt="...">
                </figure>
                <p>任务</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/finances" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/finance.svg') }}" alt="...">
                </figure>
                <p>财务</p>
                </a>
            </div>
            <div class="column col-xs-4 text-center mt-2">
                <a href="/orgs" class="text-dark">
                <figure class="avatar avatar-lg bg-gray">
                    <img src="{{ asset('images/config.svg') }}" alt="...">
                </figure>
                <p>系统设置</p>
                </a>
            </div>


        </div>
</div>

@endsection
