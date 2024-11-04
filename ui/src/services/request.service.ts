import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class RequestService {

  constructor(private http: HttpClient) { }

  public post(apiPath: string, requestData: object, config ?: []) {
    return this.http.post(apiPath, requestData);
  }

}
