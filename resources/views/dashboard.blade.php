@php
    $userRole = auth()->user()->getRoleNames()->first();
    
    switch($userRole) {
        case 'admin':
            $dashboardView = 'dashboards.admin';
            break;
        case 'bo':
            $dashboardView = 'dashboards.bo';
            break;
        case 'cab':
            $dashboardView = 'dashboards.cab';
            break;
        case 'dai':
            $dashboardView = 'dashboards.dai';
            break;
        case 'chef_division':
            $dashboardView = 'dashboards.chef_division';
            break;
        case 'sg':
            $dashboardView = 'dashboards.sg';
            break;
        default:
            $dashboardView = 'dashboards.division';
    }
@endphp

@include($dashboardView)