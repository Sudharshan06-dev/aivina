import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {
    APP_TITLE, AUTH_ROUTE, AUTH_SSO_PATH,PASSWORD_MAXLENGTH, PASSWORD_MINLENGTH
} from "../../environment";
import {RequestService} from "../../services/request.service";
import {LABELS} from "../utilities/labels";
import {FormBuilder, FormGroup, ReactiveFormsModule, Validators} from "@angular/forms";
import {RequiredAstrixDirective} from "../../directives/required-astrix.directive";
import {CommonModule} from "@angular/common";
import {ActivatedRoute, Router} from "@angular/router";
import {ToasterHelper} from "../../services/toast.service";
import {LocalStorageHelper} from "../../services/local-storage.service";
import {SKIP_AUTH_TRUE} from "../../interceptors/auth.interceptor";

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
export class LoginComponent implements OnInit{

    //Labels
    protected readonly COMMON_LABELS = LABELS.COMMON;

    protected readonly LOGIN_LABELS = LABELS.LOGIN;

    protected readonly APP_TITLE = APP_TITLE;

    protected readonly PASSWORD_MAXLENGTH = PASSWORD_MAXLENGTH;

    protected readonly PASSWORD_MINLENGTH = PASSWORD_MINLENGTH;

    public signUpForm !: FormGroup;

    public signInForm !: FormGroup;


    @ViewChild('flipper') flipperRef !: ElementRef;

    ngOnInit() {

        this.route.queryParams.subscribe(params => {
            if(params['error']) {
                this.toastService.error({message: params['error'], title: 'Error!'})
            }

            this.router.navigate([], {
                queryParams: { error: null }, // Set error to null
                queryParamsHandling: 'merge', // Merge with other query params
                replaceUrl: true, // Replace the current URL in the browser history
            });
        });

        //Set whole localstorage item to null
        localStorage.clear();
    }

    constructor(
        private request: RequestService,
        private fb: FormBuilder,
        private toastService: ToasterHelper,
        private route: ActivatedRoute,
        private localStorageService: LocalStorageHelper,
        private router: Router) {

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

        this.request.post(AUTH_ROUTE + '/register-user', this.signUpForm.getRawValue(), [SKIP_AUTH_TRUE]).subscribe({
            next: (data: any) => {
                // Handle successful response here
                this.toastService.success(data);
                this.flipCard();
            },

            error: (err: any) => {
                // Handle error response here
                this.toastService.error(err?.error);
            }
        });
    }

    public loginUser() {

        this.request.post(AUTH_ROUTE + '/login-user', this.signInForm.getRawValue(), [SKIP_AUTH_TRUE]).subscribe({
            next: (data: any) => {
                // Handle successful response here
                this.localStorageService.storeItem('access_token', data?.access_token)
                this.localStorageService.storeItem('user_details', data?.user_details)
                this.router.navigate(['/main-dashboard']);
            },

            error: (err: any) => {
                // Handle error response here
                this.toastService.error(err?.error);
            }
        });
    }

    public redirectToGoogleAuth(type: string) {
        window.location.href = AUTH_SSO_PATH+ '/redirect?type=' + type;
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
