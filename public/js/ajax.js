class Ajax {

    constructor(responseObj = null) {
        this.response = responseObj;
    }
}

class AjaxForm extends Ajax {

  constructor(parameters) {
    super();
    if (parameters.element === null) {
      console.error('AjaxForm contsructor: element undefined');
    }
    this.formObj = $(parameters.element);
    this.element = parameters.element;
    this.done = parameters.done || null;
    this.ajax =  parameters.ajax || null;
  }

  /**
     * Bind event to element and call XHR method
     * 
     * @returns {void}
     */
  init() {
    if ($(this.element).length == 1) {

      $(document).on('submit', this.element, (event) => {
        event.preventDefault();
        if (this.done != null && typeof this.done == 'function') {
          this.jqXHR().done(this.done);
        } else {
          this.jqXHR();
        }
      });
    }else{
      console.error('element length: '+$(this.element).length);
    }
  }

  resetForm() {
    this.formObj.find(':input:not(:checkbox):not(:radio)').removeClass('is-valid').removeClass('is-invalid');
    this.formObj.find('.invalid-feedback').remove();
    this.formObj.find(':input:not(:submit)').val('');
  }

  /**
     * Clear error messages and set all input to success state. 
     * @returns {void}
     */
  setAllFieldToSuccess() {
    this.formObj.find(':input:not(:checkbox):not(:radio)').removeClass('is-invalid').addClass('is-valid');
    this.formObj.find('.invalid-feedback').remove();
    this.formObj.find('.array-error').remove();
  }

  errorHandler(jqXHR) {

    if (jqXHR.status == 422) {
      // clear error messages and set all input to success state
      this.setAllFieldToSuccess();
      // set error messages if input name exists in error object
      $.each(jqXHR.responseJSON.errors, (inputNameAttribute, messages) => {
        let inputObject;
        if (inputNameAttribute.indexOf('.') > 0) {
          // if input is an multi dimension array the error key will contain dot

          let errorKeySegments = inputNameAttribute.split('.');

          if(errorKeySegments.length == 3){
            inputObject = this.formObj.find('[name="' + errorKeySegments[0] + '[' + errorKeySegments[1] + '][' + errorKeySegments[2] + ']' + '"]');
          }else if(errorKeySegments.length == 2){
           
            inputObject = this.formObj.find('[name="' + errorKeySegments[0] + '[' + errorKeySegments[1] + ']' + '"]');
           
          }

        } else if (this.formObj.find(' [name="' + inputNameAttribute + '[]"]').length) {
          // if input is one dimension array
          inputObject = this.formObj.find(' [name="' + inputNameAttribute + '[]"]:first');
        } else if (this.formObj.find(' [name="' + inputNameAttribute + '"]').length) {
          inputObject = this.formObj.find(' [name="' + inputNameAttribute + '"]');
        }

        if (typeof inputObject === 'undefined') {
          this.formObj.prepend('<p class="text-danger array-error"><i class="fa fa-exclamation"></i> ' + messages[0] + '</p>');
        } else {
          inputObject.removeClass('is-valid').addClass('is-invalid');
          inputObject.closest('.form-group').append('<div class="invalid-feedback" style="display: initial;">' + messages[0] + '</div>');
        }
      });

    }

    this.formObj.find('button:submit i.processing-fa').remove();
    this.formObj.find('button:submit').prop('disabled', false);
  }

  /**
     * jqXHR 
     * 
     * @param object this.ajax - jquery ajax this.ajax
     * @returns jquery XMLHttpRequest object 
     */
  jqXHR() {

    let ajaxSettings = {
      url: this.formObj.attr('action'),
      method: this.formObj.attr('method'),
      data: this.formObj.serialize(),
      //dataType: "json",
      beforeSend: () => {
        this.formObj.find('button:submit').prepend('<i class="fa fa-cog fa-spin processing-fa"></i> ').prop('disabled', true);
      },
      success: (response) => {
        this.response = response;
        this.formObj.find('button:submit i.processing-fa').remove();
        this.formObj.find('button:submit').prop('disabled', false);
        if (typeof this.response.resetForm != 'undefined') {
          this.resetForm();
        }
        if (typeof this.response.success != 'undefined') {
          this.setAllFieldToSuccess();
        }

      }
    };

    if (this.ajax != null) {
      $.each(this.ajax, function (index, value) {
        ajaxSettings[index] = value;
      });
    }

    // ha van file input
    if (this.formObj.find('input[type="file"]').length) {
      let formData = new FormData(this.formObj.get(0))
      ajaxSettings['contentType'] = false;
      ajaxSettings['processData'] = false;
      ajaxSettings['data'] = formData;
    }

    let ajax = $.ajax(ajaxSettings).fail((jqXHR, textStatus) => {
      this.errorHandler(jqXHR);
    });

    if (this.done != null && typeof this.done == 'function') {
      ajax.done(this.done);
    }

    return ajax;
  }

}
/*
 * Példák
 * 
 let createForm = new AjaxForm({element: '#create-form'});
 createForm.formObj.on('submit', (event) => {
 event.preventDefault();
 createForm.jqXHR().done((response) => {
 this.table.ajax.reload();
 createForm.resetForm();
 });
 });
 ----------------
 $(document).on('submit','form.ajax',function(e){
 e.preventDefault();
 new AjaxForm({element: '#form'}).jqXHR();
 });
 -------------------------------
 $(document).on('submit', 'form.update-user-form', (event) => {
 event.preventDefault();
 let updateForm = new AjaxForm({element: event.currentTarget});
 updateForm.jqXHR().done((response) => {
 });
 });
 new AjaxForm({element: '#addstock-form',
 done: (response) => {
 $('#stock').prepend(response.view);
 $('#product-stock').html(response.productStock);
 }}).init();
 */

class AjaxLink extends Ajax {

    constructor(parameters) {
        super();
        if (parameters.element === null) {
            console.error('AjaxLink contsructor: element undefined');
        }
        this.linkObj = $(parameters.element);
        this.done = parameters.done || null;
    }

    /**
     * jqXHR 
     * 
     * @param object settings - jquery ajax settings
     * @returns jquery XMLHttpRequest object 
     */
    jqXHR(settings) {

        let ajaxSettings = {
            url: this.linkObj.attr('href'),
            method: 'GET',
            dataType: "json",
            success: (response) => {
                this.response = response;
            }
        };
        if (settings) {
            $.each(settings, function (index, value) {
                if (index in ajaxSettings) {
                    ajaxSettings[index] = value;
                }
            });
        }
        
        let ajax = $.ajax(ajaxSettings);
        
         if (this.done != null && typeof this.done == 'function') {
            ajax.done(this.done);
        }

        return ajax;
    }

}