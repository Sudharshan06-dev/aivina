import { Routes } from '@angular/router';
import {LoginComponent} from './login/login.component';
import {MainDashboardComponent} from "./main-dashboard/main-dashboard.component";

export const routes: Routes = [
  {path: '', component: LoginComponent},
  {path: 'login', component: LoginComponent},
  {path: 'main-dashboard', component: MainDashboardComponent}
];
