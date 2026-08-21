<?php // routes/breadcrumbs.php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('contacts', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Contacts', route('contacts.view'));
});

Breadcrumbs::for('timeclock', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Time Clock', route('attendance.index'));
});

Breadcrumbs::for('pricelist', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Vendor Pricelists', route('pricelist.lennox'));
});

Breadcrumbs::for('credentials', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Vendor Credentials', route('pricelist.lennox'));
});

Breadcrumbs::for('drumlabels', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Drum Labels', route('warehouse.drumlabels'));
});

Breadcrumbs::for('warehouseforms', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Warehouse Forms', route('warehouse.miscdocs'));
});

Breadcrumbs::for('admap', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('AD Mapping', route('remit.mapping'));
});

Breadcrumbs::for('remitreport', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('AD Remit', route('remit.dateform'));
});

Breadcrumbs::for('remitpost', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('AD Remit', route('remit.report'));
});

Breadcrumbs::for('freightlog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('FreightLog', route('freightlog'));
});

Breadcrumbs::for('newfreight', function (BreadcrumbTrail $trail) {
    $trail->parent('freightlog');
    $trail->push('Create Log', route('freightlog.create'));
});


Breadcrumbs::for('calendar', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Time-Off Calendar', route('calendar'));
});

Breadcrumbs::for('request', function (BreadcrumbTrail $trail) {
    $trail->parent('calendar');
    $trail->push('New Request', route('timeoff.requestform'));
});

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

Breadcrumbs::for('pendingrequests', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Pending Requests', route('manager.requests'));
});

Breadcrumbs::for('allrequests', function (BreadcrumbTrail $trail) {
    $trail->parent('pendingrequests');
    $trail->push('All Future Requests', route('manager.allrequests'));
});

