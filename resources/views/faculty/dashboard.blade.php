@extends('layouts.main')

@section('title','Faculty Dashboard')

@section('content')
<div class="container-fluid">
  <h1 class="my-4">Faculty Dashboard</h1>

  {{-- Summary Cards --}}
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-header">Total Responses</div>
        <div class="card-body">
          <h5 class="card-title">{{ $responses->count() }}</h5>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success">
        <div class="card-header">Employed</div>
        <div class="card-body">
          <h5 class="card-title">{{ $employment['Employed'] ?? 0 }}</h5>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-danger">
        <div class="card-header">Unemployed</div>
        <div class="card-body">
          <h5 class="card-title">{{ $employment['Unemployed'] ?? 0 }}</h5>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-info">
        <div class="card-header">Self‑employed</div>
        <div class="card-body">
          <h5 class="card-title">{{ $employment['Self-employed'] ?? 0 }}</h5>
        </div>
      </div>
    </div>
  </div>

  {{-- Export Dropdown --}}
<div class="mb-3">
  <div class="dropdown">
    <button class="export-button btn btn-success dropdown-toggle d-flex align-items-center" type="button" id="exportDropdown" data-toggle="dropdown">
      <i class="fas fa-file-csv mr-2"></i> <span style= "padding-right: 22px;">Export CSV </span>
      <i class="fas fa-chevron-down ml-1 dropdown-arrow" style="font-size:12px;"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="exportDropdown">
      <a class="dropdown-item export-option" href="#" data-status="">All</a>
      <a class="dropdown-item export-option" href="#" data-status="Employed">Employed</a>
      <a class="dropdown-item export-option" href="#" data-status="Unemployed">Unemployed</a>
      <a class="dropdown-item export-option" href="#" data-status="Self-employed">Self‑employed</a>
    </div>
  </div>
</div>


  {{-- Tabs --}}
  <ul class="nav nav-tabs" id="dashTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#charts">Charts</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#table">Table</a>
    </li>
  </ul>
  <div class="tab-content mt-3">
    {{-- Charts Tab --}}
    <div class="tab-pane fade show active" id="charts">
      <div class="row">
        @foreach([
          ['title'=>'Employment Status','id'=>'empChart'],
          ['title'=>'Graduation Year','id'=>'yearChart'],
          ['title'=>'Job Related to Degree','id'=>'relChart'],
          ['title'=>'Avg Time to First Job (days)','id'=>'ttfjChart'],
        ] as $chart)
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-header">{{ $chart['title'] }}</div>
            <div class="card-body">
              <canvas id="{{ $chart['id'] }}" style="max-height:250px;"></canvas>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Table Tab --}}
<div class="table tab-pane fade" id="table">

  {{-- Scrollable Table Container --}}
  <div class="table-scroll">
    <table id="responsesTable" class="table table-striped">
      <thead>
        <tr>
          @foreach(array_keys((array)$responses->first()) as $col)
            @php
              $map = ['created_at'=>'Date answered','updated_at'=>'Date optional answered'];
              $label = $map[$col] ?? ucwords(str_replace('_',' ',$col));
            @endphp
            <th>{{ $label }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($responses as $r)
          <tr>
            @foreach((array)$r as $val)
              <td>{{ $val }}</td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Recently Answered --}}
  <h4 class="recent-answered-title">Recently Answered</h4>
  <div class="recent-card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="thead-light">
          <tr>
            <th>Alumni ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Date Answered</th>
          </tr>
        </thead>
        <tbody>
          @foreach(\DB::table('form_responses')->orderBy('created_at','desc')->limit(10)->get() as $r)
            <tr>
              <td>{{ $r->alumni_id }}</td>
              <td>{{ $r->first_name }} {{ $r->last_name }}</td>
              <td>{{ $r->present_employment_status }}</td>
              <td>{{ \Carbon\Carbon::parse($r->created_at)->format('Y-m-d H:i') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
$(function(){
  // DataTable
  $('#responsesTable').DataTable({
    dom: 'Bfrtip',
    buttons: ['colvis']
  });

  // Export
  $('.export-option').click(function(e){
    e.preventDefault();
    const status = $(this).data('status');
    Swal.fire({
      title: `Export ${status||'All'}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, export!'
    }).then(res => {
      if(res.isConfirmed){
        let url = "{{ route('faculty.export') }}";
        if(status) url += '?status=' + encodeURIComponent(status);
        window.location = url;
      }
    });
  });

  // Charts
  const makeChart = (ctx, type, data, opts={}) =>
    new Chart(ctx, { type, data, options: opts });

  makeChart($('#empChart'), 'pie', {
    labels: Object.keys(@json($employment)),
    datasets:[{ data:Object.values(@json($employment)) }]
  });

  makeChart($('#yearChart'), 'bar', {
    labels: Object.keys(@json($years)),
    datasets:[{
      label:'Responses',
      data:Object.values(@json($years))
    }]
  }, { scales:{ y:{ beginAtZero:true } } });

  makeChart($('#relChart'), 'doughnut', {
    labels:Object.keys(@json($related)),
    datasets:[{ data:Object.values(@json($related)) }]
  });

  makeChart($('#ttfjChart'), 'line', {
    labels:Object.keys(@json($ttfj)),
    datasets:[{
      label:'Avg days',
      data:Object.values(@json($ttfj)),
      tension:0.3
    }]
  }, { scales:{ y:{ beginAtZero:true } } });
});
</script>

@endsection
