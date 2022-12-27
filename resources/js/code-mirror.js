import CodeMirror from 'codemirror';
import 'codemirror/addon/edit/matchbrackets';
import 'codemirror/mode/javascript/javascript.js';
import 'codemirror/mode/htmlmixed/htmlmixed.js';
import 'codemirror/mode/xml/xml.js';
import 'codemirror/mode/css/css.js';
import 'codemirror/mode/clike/clike.js';
import 'codemirror/mode/php/php.js';

const input = document.getElementById("code");

var editor = CodeMirror.fromTextArea(input, {
    lineNumbers: true,
    theme: 'dracula',
    indentUnit: 4,
    indentWithTabs: true,
    tabSize: 4,
    lineWrapping: true,


    mode: {name: "javascript", json: true},
    extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
    foldOptions: {
      widget: (from, to) => {
        var count = undefined;
        // Get open / close token
        var startToken = '{', endToken = '}';
        var prevLine = window.editor_json.getLine(from.line);
        if (prevLine.lastIndexOf('[') > prevLine.lastIndexOf('{')) {
          startToken = '[', endToken = ']';
        }

        // Get json content
        var internal = window.editor_json.getRange(from, to);
        var toParse = startToken + internal + endToken;

        // Get key count
        try {
          var parsed = JSON.parse(toParse);
          count = Object.keys(parsed).length;
        } catch(e) { }

        return count ? `\u21A4${count}\u21A6` : '\u2194';
      }
    }
});

editor.on("change", function() {
    input.dispatchEvent(new CustomEvent('input', {
        detail: editor.getValue(),
        bubbles: true,
    }));
});
