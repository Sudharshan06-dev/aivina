import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core';
import { provideRouter } from '@angular/router';

import { routes } from './app.routes';
import {provideHttpClient, withInterceptors} from "@angular/common/http";
import {provideAnimations} from "@angular/platform-browser/animations";
import {provideToastr} from "ngx-toastr";
import {TOAST_CONFIGURATION} from "../environment";
import {authInterceptor} from "../interceptors/auth.interceptor";
import {spinnerInterceptor} from "../interceptors/spinner.interceptor";

export const appConfig: ApplicationConfig = {
  providers: [
      provideZoneChangeDetection({ eventCoalescing: true }),
      provideRouter(routes),
      provideAnimations(),
      provideHttpClient(withInterceptors([spinnerInterceptor, authInterceptor])),
      provideToastr(TOAST_CONFIGURATION),
  ]
};