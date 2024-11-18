import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {LocalStorageHelper} from "../../../services/local-storage.service";

@Component({
  selector: 'app-google-callback',
  standalone: true,
  imports: [],
  template: ``,
  styles: ``
})
export class GoogleCallbackComponent implements OnInit{

  constructor(private route: ActivatedRoute, private router: Router, private localStorageService: LocalStorageHelper) {
  }

  ngOnInit() {

    this.route.queryParams.subscribe((params: any) => {

      if(params['error']) {
        this.router.navigate(['/login'], {
          queryParams: { error: params['error'] }
        })

        return;
      }

      if(params['token']) {
        this.localStorageService.storeItem('access_token', params['token']);
        this.router.navigate(['/main-dashboard'])
      }

    });
  }
}
