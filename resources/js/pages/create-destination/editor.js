// resources/js/pages/create-destination/editor.js


document.addEventListener('DOMContentLoaded', function () {
    let editor;

    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: {
                items: [
                    'heading', '|', 'bold', 'italic', 'link',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|', 'blockQuote', 'undo', 'redo'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .then(newEditor => {
            editor = newEditor;

            const oldValue = document.querySelector('#description').dataset.old || '';
            if (oldValue.trim() !== '') {
                editor.setData(oldValue);
            }

            editor.model.document.on('change:data', () => {
                clearTimeout(window.previewTimeout);
                window.previewTimeout = setTimeout(() => updateContentPreview(editor), 300);
            });

            updateContentPreview(editor);
        })
        .catch(error => console.error('CKEditor init error:', error));

    document.querySelector('form')?.addEventListener('submit', function () {
        if (editor) {
            document.querySelector('#description').value = editor.getData();
        }
    });

    function updateContentPreview(editor) {
        const data = editor.getData();
        const preview = document.getElementById('content-preview');
        if (preview) {
            preview.innerHTML = data || '<em>Tidak ada konten untuk ditampilkan.</em>';
        }
    }
});
