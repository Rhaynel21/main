

<?php $__env->startSection('title','Faculty Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
  <h1 class="my-4">Faculty Dashboard</h1>

  
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-header">Total Responses</div>
        <div class="card-body">
          <h5 class="card-title"><?php echo e($responses->count()); ?></h5>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success">
        <div class="card-header">Employed</div>
        <div class="card-body">
          <h5 class="card-title"><?php echo e($employment['Employed'] ?? 0); ?></h5>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-danger">
        <div class="card-header">Unemployed</div>
        <div class="card-body">
          <h5 class="card-title"><?php echo e($employment['Unemployed'] ?? 0); ?></h5>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-info">
        <div class="card-header">Self‑employed</div>
        <div class="card-body">
          <h5 class="card-title"><?php echo e($employment['Self-employed'] ?? 0); ?></h5>
        </div>
      </div>
    </div>
  </div>

  
  <div class="mb-3">
    <div class="dropdown">
      <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown">
        <i class="fas fa-file-csv"></i> Export CSV
      </button>
      <div class="dropdown-menu" aria-labelledby="exportDropdown">
        <a class="dropdown-item export-option" href="#" data-status="">All</a>
        <a class="dropdown-item export-option" href="#" data-status="Employed">Employed</a>
        <a class="dropdown-item export-option" href="#" data-status="Unemployed">Unemployed</a>
        <a class="dropdown-item export-option" href="#" data-status="Self-employed">Self‑employed</a>
      </div>
    </div>
  </div>

  
  <ul class="nav nav-tabs" id="dashTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#charts">Charts</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#table">Table</a>
    </li>
  </ul>
  <div class="tab-content mt-3">
    
    <div class="tab-pane fade show active" id="charts">
      <div class="row">
        <?php $__currentLoopData = [
          ['title'=>'Employment Status','id'=>'empChart'],
          ['title'=>'Graduation Year','id'=>'yearChart'],
          ['title'=>'Job Related to Degree','id'=>'relChart'],
          ['title'=>'Avg Time to First Job (days)','id'=>'ttfjChart'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-header"><?php echo e($chart['title']); ?></div>
            <div class="card-body">
              <canvas id="<?php echo e($chart['id']); ?>" style="max-height:250px;"></canvas>
            </div>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    
    <div class="tab-pane fade" id="table">
      <div style="overflow-x:auto; white-space: nowrap; margin-bottom:2rem;">
        <table id="responsesTable" class="table table-striped" style="min-width:1200px;">
          <thead>
            <tr>
              <?php $__currentLoopData = array_keys((array)$responses->first()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $map = ['created_at'=>'Date answered','updated_at'=>'Date optional answered'];
                  $label = $map[$col] ?? ucwords(str_replace('_',' ',$col));
                ?>
                <th><?php echo e($label); ?></th>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $responses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <?php $__currentLoopData = (array)$r; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <td><?php echo e($val); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>

      
      <h4>Recently Answered</h4>
      <div class="card">
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
              <?php $__currentLoopData = \DB::table('form_responses')
                    ->orderBy('created_at','desc')
                    ->limit(10)
                    ->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($r->alumni_id); ?></td>
                  <td><?php echo e($r->first_name); ?> <?php echo e($r->last_name); ?></td>
                  <td><?php echo e($r->present_employment_status); ?></td>
                  <td><?php echo e(\Carbon\Carbon::parse($r->created_at)->format('Y-m-d H:i')); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
        let url = "<?php echo e(route('faculty.export')); ?>";
        if(status) url += '?status=' + encodeURIComponent(status);
        window.location = url;
      }
    });
  });

  // Charts
  const makeChart = (ctx, type, data, opts={}) =>
    new Chart(ctx, { type, data, options: opts });

  makeChart($('#empChart'), 'pie', {
    labels: Object.keys(<?php echo json_encode($employment, 15, 512) ?>),
    datasets:[{ data:Object.values(<?php echo json_encode($employment, 15, 512) ?>) }]
  });

  makeChart($('#yearChart'), 'bar', {
    labels: Object.keys(<?php echo json_encode($years, 15, 512) ?>),
    datasets:[{
      label:'Responses',
      data:Object.values(<?php echo json_encode($years, 15, 512) ?>)
    }]
  }, { scales:{ y:{ beginAtZero:true } } });

  makeChart($('#relChart'), 'doughnut', {
    labels:Object.keys(<?php echo json_encode($related, 15, 512) ?>),
    datasets:[{ data:Object.values(<?php echo json_encode($related, 15, 512) ?>) }]
  });

  makeChart($('#ttfjChart'), 'line', {
    labels:Object.keys(<?php echo json_encode($ttfj, 15, 512) ?>),
    datasets:[{
      label:'Avg days',
      data:Object.values(<?php echo json_encode($ttfj, 15, 512) ?>),
      tension:0.3
    }]
  }, { scales:{ y:{ beginAtZero:true } } });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/faculty/dashboard.blade.php ENDPATH**/ ?>