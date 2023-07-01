import { helper } from '@ember/component/helper';

function currentYear() {
    return (new Date()).getFullYear();
}

export default helper(currentYear);
