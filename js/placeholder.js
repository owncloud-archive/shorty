$(document).ready(function(){
//  var inputs = document.querySelectorAll("input[placeholder], textarea[placeholder]"),
  var inputs = $("input[placeholder],textarea[placeholder]"),

    // Set caret at the beginning of the input
    setCaret = function (evt) {
        if (this.value === this.placeholderText) {
          this.setSelectionRange(0, 0);
          evt.preventDefault();
          evt.stopPropagation();
          return false;
        }
    },

    // Clear placeholder value at user input
    clearPlaceholder = function (evt) {
        if (evt.keyCode !== 9) {
          if (this.value === this.placeholderText) {
            this.value = "";
            this.className = "active";
            if (this.oType === "password") {
              this.type = "password";
            }
          }
        }
    },

    restorePlaceholder = function () {
        if (this.value.length === 0) {
          this.value = this.placeholderText;
          setCaret.apply(this, arguments);
          this.className = "";
          if (this.type === "password") {
            this.type = "text";
          }
        }
    },

    clearPlaceholderAtSubmit = function (evt) {
        for (var i=0, input; i<l; i++) {
            input = inputs[i];
            if (input.value === input.placeholderText) {
                input.value = "";
            }
        }
    },

    valOrPlaceholder = function () {
      if (   this.attr('saveholder') !=='undefined'
          && this.attr('saveholder') !==false
          && this.attr('saveholder') !=='false' )
        return input.value;
      else 
        if (input.value === input.placeholderText)
          return '';
        else
          return input.value;
    };

  for (var i=0, input; i<inputs.length; i++) {
    input = inputs[i];
    if (input.value.length === 0) {
        input.placeholderText = input.getAttribute("placeholder");
        input.removeAttribute("placeholder");
        input.value = input.placeholderText;
        if (input.type === "password") {
            input.oType = input.type;
            input.type = "text";
        }
    }
    else {
        input.className = "active";
    }

    // Apply events for placeholder handling
    input.addEventListener("focus", setCaret, false);
    input.addEventListener("click", setCaret, false);
    input.addEventListener("keydown", clearPlaceholder, false);
    input.addEventListener("keyup", restorePlaceholder, false);
    input.addEventListener("blur", restorePlaceholder, false);

    // clear all default placeholder values from the form at submit
    input.form.addEventListener("submit", clearPlaceholderAtSubmit, false);
  };
})
