import {Component, ElementRef, SkipSelf, ViewChild} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {APP_TITLE, LARAVEL_ROUTE} from "../../environment";
import {DataCommunicationService} from "../../services/data-communication.service";
import {RequestService} from "../../services/request.service";
import {LABELS} from "../labels";
import {log} from "@angular-devkit/build-angular/src/builders/ssr-dev-server";

@Component({
    selector: 'app-login',
    standalone: true,
    imports: [],
    templateUrl: './login.component.html',
    styleUrl: './login.component.css'
})
export class LoginComponent {

    //Labels
    protected readonly commonLabels = LABELS.COMMON;

    protected readonly loginLabels = LABELS.LOGIN;

    protected readonly APP_TITLE = APP_TITLE;

    @ViewChild('flipper') flipperRef !: ElementRef;

    constructor(private request: RequestService) {
        this.loginUser();
    }

    //flip login card
    public flipCard() {
        this.flipperRef.nativeElement.classList.toggle('flip')
    }

    public loginUser() {

        this.request.post(LARAVEL_ROUTE + '/register-user', {username: 'test', password: 'test'}).subscribe({
            next: (data: any) => {
                // Handle successful response here
                console.log("Login successful:", data);
            },

            error: (err: any) => {
                // Handle error response here
                console.error("Login error:", err);
            }
        });
    }
}
