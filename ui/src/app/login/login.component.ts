import {Component, ElementRef, ViewChild} from '@angular/core';
import {APP_TITLE, LARAVEL_ROUTE, PASSWORD_MAXLENGTH, PASSWORD_MINLENGTH} from "../../environment";
import {RequestService} from "../../services/request.service";
import {LABELS} from "../utilities/labels";
import {FormBuilder, FormGroup, ReactiveFormsModule, Validators} from "@angular/forms";
import {RequiredAstrixDirective} from "../../directives/required-astrix.directive";
import {CommonModule} from "@angular/common";
import {ToastrService} from "ngx-toastr";
import {Router} from "@angular/router";

@Component({
    selector: 'app-login',
    standalone: true,
    imports: [
        RequiredAstrixDirective,
        ReactiveFormsModule,
        CommonModule
    ],
    templateUrl: './login.component.html',
    styleUrl: './login.component.css'
})
export class LoginComponent {

    //Labels
    protected readonly COMMON_LABELS = LABELS.COMMON;

    protected readonly LOGIN_LABELS = LABELS.LOGIN;

    protected readonly APP_TITLE = APP_TITLE;

    protected readonly PASSWORD_MAXLENGTH = PASSWORD_MAXLENGTH;

    protected readonly PASSWORD_MINLENGTH = PASSWORD_MINLENGTH;

    public signUpForm !: FormGroup;

    public signInForm !: FormGroup;

    @ViewChild('flipper') flipperRef !: ElementRef;

    constructor(private request: RequestService, private fb: FormBuilder, private toastService: ToastrService, private route: Router) {

        this.signUpForm = this.fb.group({
            firstname: [null, [Validators.required, Validators.pattern('[a-zA-Z]+'), Validators.maxLength(35)]],
            lastname: [null, [Validators.required, Validators.pattern('[a-zA-Z]+'), Validators.maxLength(35)]],
            username: [null, [Validators.required, Validators.pattern('[a-zA-Z0-9]+')]],
            email: [null, [Validators.required, Validators.email]],
            password: [null, [Validators.required, Validators.minLength(8), Validators.maxLength(15)]]
        });

        this.signInForm = this.fb.group({
            username: [null, [Validators.required, Validators.pattern('[a-zA-Z0-9]+')]],
            password: [null, [Validators.required, Validators.minLength(8), Validators.maxLength(15)]]
        });
    }

    public createUser() {

        this.request.post(LARAVEL_ROUTE + '/register-user', this.signUpForm.getRawValue()).subscribe({
            next: (data: any) => {
                // Handle successful response here
                this.toastService.success(data.title, data.message);
                this.flipCard();
            },

            error: (err: any) => {
                // Handle error response here
                this.toastService.error(err?.error.title, err?.error.message);
            }
        });
    }

    public loginUser() {

        this.request.post(LARAVEL_ROUTE + '/login-user', this.signInForm.getRawValue()).subscribe({
            next: (data: any) => {
                // Handle successful response here
                this.route.navigate(['/main-dashboard']);
                //this.toastService.success(data.title, data.message);
            },

            error: (err: any) => {
                // Handle error response here
                this.toastService.error(err?.error.title, err?.error.message);
            }
        });
    }

    //flip login card
    public flipCard() {
        this.signInForm.reset();
        this.signUpForm.reset()
        this.flipperRef.nativeElement.classList.toggle('flip')
    }

    get firstname() {
        return this.signUpForm.get('firstname')
    }

    get lastname() {
        return this.signUpForm.get('lastname')
    }

    get email() {
        return this.signUpForm.get('email')
    }

    get initialUserName() {
        return this.signUpForm.get('username');
    }

    get initialPassword() {
        return this.signUpForm.get('password')
    }

    get userName() {
        return this.signInForm.get('username');
    }

    get password() {
        return this.signInForm.get('password');
    }
}
