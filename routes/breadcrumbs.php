<?php // routes/breadcrumbs.php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


Breadcrumbs::for('attendancedash', function (BreadcrumbTrail $trail) {
    $trail->push('Attendance Dash', route('dashboard.attendance'));
});

Breadcrumbs::for('payperiodsummary', function (BreadcrumbTrail $trail, $period) {
    $trail->parent('attendancedash');
    $trail->push(ucwords($period) . ' Payperiod', route('attendance.periodreport', $period));
});

Breadcrumbs::for('userdetails', function (BreadcrumbTrail $trail, $period, $username) {
    $trail->parent('payperiodsummary', $period );
    $trail->push($username);
});

Breadcrumbs::for('punchdetails', function (BreadcrumbTrail $trail) {
    $trail->parent('attendancedash');
    $trail->push('Edit Punch Entry');
});

