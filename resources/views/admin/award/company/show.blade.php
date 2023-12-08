@extends('layouts.admin')
@section('content')

<div class="card">
	<div class="card-header">
		{{ trans('global.show') }} {{ trans('cruds.services.title') }}
	</div>

	<div class="card-body">

		<div class="form-group">
			<div class="form-group">
				<a class="btn btn-default" href="{{ route('admin.services.index') }}">
					{{ trans('global.back_to_list') }}
				</a>
			</div>

			@foreach($categories as $category)
			<div class="card mb-3">
				<div class="card-header">{{ $category->title }}</div>
				<div class="card-body">

					@foreach($category->companies as $company)
					<div class="form-check">
						<input class="form-check-input" type="radio" value="{{ $company->id }}" id="award_cat_{{ $category->id }}_{{ $company->id }}" name="award_vote[{{ $category->id }}]">
						<label class="form-check-label" for="award_cat_{{ $category->id }}_{{ $company->id }}">
							{{ $company->company_detail->title }} <a href="{{ $company->company_detail->url }}" target="_blank">[view]</a>
						</label>
					</div>
					@endforeach
				</div>
			</div>
			@endforeach

			<div class="form-group">
				<a class="btn btn-default" href="{{ route('admin.services.index') }}">
					{{ trans('global.back_to_list') }}
				</a>
			</div>
		</div>
	</div>
</div>



@endsection