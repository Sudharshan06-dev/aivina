import {Component, EventEmitter, Input, Output} from '@angular/core';
import {DrawerComponent, KENDO_LAYOUT} from "@progress/kendo-angular-layout";
import {KENDO_BUTTON} from "@progress/kendo-angular-buttons";
import {animate, state, style, transition, trigger} from "@angular/animations";
import {RouterLink, RouterLinkActive} from "@angular/router";
import {CommonModule} from "@angular/common";

interface MenuItem {
    icon: string;
    label: string;
    route: string;
    children?: MenuItem[];
}

@Component({
    selector: 'app-sidebar',
    standalone: true,
  imports: [
    DrawerComponent,
    KENDO_LAYOUT,
    KENDO_BUTTON,
    CommonModule,
    RouterLink,
    RouterLinkActive
  ],
    templateUrl: './sidebar.component.html',
    styleUrl: './sidebar.component.css',
    animations: [
        trigger('sidebarState', [
            state('collapsed', style({
                width: '60px'
            })),
            state('expanded', style({
                width: '250px'
            })),
            transition('collapsed <=> expanded', [
                animate('200ms ease-in-out')
            ])
        ])
    ]
})

export class SidebarComponent {

    @Input() isExpanded = true;
    @Output() toggleSidebarEvent = new EventEmitter<boolean>();

    public menuItems: MenuItem[] = [
        {icon: 'fa-chart-line', label: 'Dashboard', route: '/dashboard'},
        {icon: 'fa-users', label: 'Users', route: '/users'},
        {icon: 'fa-cog', label: 'Settings', route: '/settings'},
        {icon: 'fa-folder', label: 'Projects', route: '/projects'},
        {icon: 'fa-calendar', label: 'Calendar', route: '/calendar'},
        {icon: 'fa-bell', label: 'Notifications', route: '/notifications'},
        {icon: 'fa-question-circle', label: 'Help', route: '/help'}
    ];

    toggleSidebar() {
        this.isExpanded = !this.isExpanded;
        this.toggleSidebarEvent.emit(this.isExpanded);
    }

}
