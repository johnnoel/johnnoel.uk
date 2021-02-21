// called when hcaptcha submits successfully
(<any>window).hCaptchaCallback = (response: string) => {
    const field = document.getElementById('contact_hcaptchaResponse') as HTMLInputElement|null;
    if (field === null) {
        console.error('Unable to find field for attaching hcaptcha response');
        return;
    }

    field.value = response;
    field.closest('form')?.submit();
};

const attachHCaptcha = (): void => {};

export default attachHCaptcha;
