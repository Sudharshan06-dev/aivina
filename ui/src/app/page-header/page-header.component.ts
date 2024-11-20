import { Component } from '@angular/core';
import {FormsModule} from "@angular/forms";

@Component({
  selector: 'app-page-header',
  standalone: true,
  imports: [
    FormsModule
  ],
  templateUrl: './page-header.component.html',
  styleUrl: './page-header.component.css'
})
export class PageHeaderComponent {

  public searchQuery: string = '';

  constructor() {
  }
}
