import '../scss/index.scss';

import parseISO from 'date-fns/parseISO';
import formatDuration from 'date-fns/formatDuration';
import intervalToDuration from 'date-fns/intervalToDuration';

const relativeElems = document.querySelectorAll('.js-relative');
if (relativeElems.length !== 0) {
    relativeElems.forEach((relElem) => {
        const from = relElem.getAttribute('datetime');
        if (from === null) {
            return;
        }

        const target = parseISO(from);
        setInterval(() => {
            const duration = intervalToDuration({
                start: target,
                end: new Date(),
            });

            relElem.innerHTML = formatDuration(duration) + ' ago';
        }, 5);
    });
}
