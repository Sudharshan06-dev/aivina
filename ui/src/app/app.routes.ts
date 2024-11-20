import { Routes } from '@angular/router';
import {LoginComponent} from './login/login.component';
import {MainDashboardComponent} from "./main-dashboard/main-dashboard.component";
import {GoogleCallbackComponent} from "./standalone/google-callback/google-callback.component";

export const routes: Routes = [
  {path: '', component: LoginComponent},
  {path: 'login', component: LoginComponent},
  {path: 'login', component: LoginComponent},
  {path: 'auth/google/callback', component: GoogleCallbackComponent},
  {path: 'main-dashboard', component: MainDashboardComponent}
];
