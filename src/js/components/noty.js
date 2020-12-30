import Noty from 'noty';
import 'noty/src/noty.scss';
import '~/scss/components/noty-custom-theme.scss';

let defaults = {
    theme: 'custom',
    layout: 'topRight',
    type: 'information',
    text: '',
    timeout: 5000,
    progressBar: true,
    closeWith: ['click', 'button'],
}

let restParams = {};

export default function noty() {

    // Arguments: layout(string), type(string), text(string), timeout(number), restParams(object)

    const types = ['alert', 'success', 'warning', 'error', 'information'];

    const layouts = ['top', 'topLeft', 'topCenter', 'topRight', 'center', 'centerLeft',
        'centerRight', 'bottom', 'bottomLeft', 'bottomCenter', 'bottomRight'];


    Object.values(arguments).forEach( arg => {

        switch (typeof(arg)) {
            case 'object': restParams = arg;
                break;
            case 'number': defaults.timeout = arg;
                break;
            case 'string': {
                if (types.some(item => item === arg)) defaults.type = arg
                else if ( arg === 'warn' ) defaults.type = 'warning';
                else if ( arg === 'info' ) defaults.type = 'information';
                else if (layouts.some(item => item === arg)) defaults.layout = arg;
                else defaults.text = arg;
            }
                break;
            case 'boolean': defaults.progressBar = arg;
        }
    });


    const params = {
        ...defaults,
        ...restParams
    };
    
    return new Noty(params).show();
}


export const VueNoty = {
    install(Vue) {
        Vue.prototype.$noty = noty;
    }
};
