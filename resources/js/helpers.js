export default {
    methods: {
        _print(content, css) {
            const w = 768
            const h = 600
            const y = window.top.outerHeight / 2 + window.top.screenY - (h / 2)
            const x = window.top.outerWidth / 2 + window.top.screenX - (w / 2)
            const newWindow = window.open('', '', `top=${y},left=${x},width=${w},height=${h},toolbar=0,titlebar=0,scrollbars=0,status=0`)

            const style = `<style>
                *, *::before, *::after { box-sizing: border-box; }
                body {
                    margin: 0;
                    padding: 0;
                    font-family: sans-serif;
                }
                ${css}
            </style>`

            newWindow.document.open()
            newWindow.document.write(`<html>`);
            newWindow.document.write(`<head>${style}</head>`);
            newWindow.document.write(`<body>`);

            newWindow.document.write(`<div id="content">${content}</div>`);

            newWindow.document.write(`</body>`);
            newWindow.document.write(`</html>`);

            newWindow.document.close()
            // setTimeout(function () {
            //     newWindow.focus()
            //     newWindow.print()
            // }, 1000);

            // newWindow.onafterprint = function () {
            //     newWindow.close()
            // }
        },

        freezeReactivity(object) {
            return JSON.parse(JSON.stringify(object))
        },
        formatDate(date) {
            return moment(date).format('YYYY-MM-DD')
        },
        formatTime(date) {
            return moment(date).format('hh:mm a')
        },
        formatTimes(date) {
            return moment(date).format('hh:mm:ss a')
        },
        formatDateTime(date) {
            return moment(date).format('YYYY-MM-DD hh:mm a')
        },
    },
    computed: {

    }
}