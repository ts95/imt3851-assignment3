/**
 * Validates a Vue.js form.
 * Assumes that Vue Resource is used.
 *
 * @param {object} component - Vue.js component
 * @param {string} redirectLink - Redirect link
 * @returns {object} - Result
 */
export default async function(component, redirectLink = '/') {
    let form = component.$el.querySelector('form');

    if (!form)
        return;

    let data = form.getAttribute('enctype') === 'multipart/form-data' ? new FormData() : {};

    for (let elem of Array.from(form.querySelectorAll('input, textarea, select'))) {
        let key = elem.getAttribute('name');
        let value = null;

        if (elem.files)
            value = elem.files[0];
        else
            value = elem.value;

        if (data.append)
            data.append(key, value);
        else
            data[key] = value;
    }

    let action = form.getAttribute('action');

    let response = await component.$http.post(action, data, {
        upload: {
            onprogress: (e) => {
                if (e.lengthComputable) {
                    let loaded = Math.round(e.loaded * 100 / e.total);
                    // if the component has a "progress" variable set
                    // in the data section, it will be populated here.
                    if (typeof component.progress !== 'undefined') {
                        if (loaded == 100)
                            component.progress = 0;
                        else
                            component.progress = loaded;
                    }
                }
            },
        },
    });

    let result = response.data;

    if (result.ok) {
        if (result.user)
            component.$root.$data.auth = result.user;

        component.$route.router.go(redirectLink);
        return result;
    }

    for (let elem of Array.from(form.querySelectorAll('*[name]'))) {
        let name = elem.getAttribute('name');
        let formGroup = elem.parentNode;

        formGroup.classList.remove('has-error');
        formGroup.classList.remove('has-success');

        let helpBlock = formGroup.querySelector('.help-block');

        if (name in result.errors) {
            formGroup.classList.add('has-error');

            if (!helpBlock) {
                helpBlock = document.createElement('small');
                helpBlock.classList.add('help-block');
                formGroup.append(helpBlock);
            }

            let errorHTML = result.errors[name].join('<br>');

            helpBlock.innerHTML = errorHTML;
        } else {
            formGroup.classList.add('has-success');

            if (helpBlock)
                helpBlock.remove();
        }
    }

    return result;
};
