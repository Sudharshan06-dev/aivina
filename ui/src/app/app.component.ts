import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import {APP_TITLE} from "../environment";

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  protected readonly APP_TITLE = APP_TITLE;
}
