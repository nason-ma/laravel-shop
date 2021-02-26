@extends('layouts.app')
@section('title', '提示')

@section('content')
    <div class="card">
        <div class="card-header">提示</div>
        <div class="card-body text-center">
            <h1>请先验证邮箱</h1>
            <a class="btn btn-primary" href="{{ route('root') }}">返回首页</a>
        </div>
    </div>
@endsection
