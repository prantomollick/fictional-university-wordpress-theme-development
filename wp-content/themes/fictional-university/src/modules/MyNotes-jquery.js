import $ from "jquery";

class MyNotes {
    constructor() {
        this.events();
    }

    events() {
        $("#my-notes").on("click", ".delete-note", this.deleteNote);
        $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
        $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
        $(".submit-note").on("click", this.createNote.bind(this));
    }

    // 3. methods (function, action...)

    editNote(e) {
        const thisNote = $(e.target).parents("li");

        if (thisNote.data("state") == "editable") {
            this.makeNoteReadOnly(thisNote);
        } else {
            this.makeNoteEditable(thisNote);
        }
    }

    makeNoteEditable(thisNote) {
        thisNote
            .find(".edit-note")
            .html('<i class="fa fa-times" aria-hidden="true"></i> cancel');
        thisNote
            .find(".note-title-field, .note-body-field")
            .removeAttr("readonly")
            .addClass("note-active-field");
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");
    }

    makeNoteReadOnly(thisNote) {
        thisNote
            .find(".edit-note")
            .html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
        thisNote
            .find(".note-title-field, .note-body-field")
            .attr("readonly", "readonly")
            .removeClass("note-active-field");
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.data("state", "cancel");
    }

    deleteNote(e) {
        const thisNote = $(e.target).parents("li");
        const noteId = thisNote.data("note-id");

        const baseUrl = universityData.root_url;
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
            },
            url: `${baseUrl}/wp-json/wp/v2/note/${noteId}`,
            type: "DELETE",
            success: (res) => {
                thisNote.slideUp();
                console.log("Congrats");
                console.log(res);
                if (res.userNoteCount < 5) {
                    $(".note-limit-message").removeClass("active");
                }
            },
            error: function (err) {
                console.log(err);
            },
        });
    }

    updateNote(e) {
        const thisNote = $(e.target).parents("li");
        const noteId = thisNote.data("note-id");

        const baseUrl = universityData.root_url;
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
            },
            url: `${baseUrl}/wp-json/wp/v2/note/${noteId}`,
            type: "PUT",
            data: {
                title: thisNote.find(".note-title-field").val(),
                content: thisNote.find(".note-body-field").val(),
            },
            success: (res) => {
                this.makeNoteReadOnly(thisNote);
                console.log("Congrats");
                console.log(res);
            },
            error: function (err) {
                console.log(err);
            },
        });
    }

    createNote(e) {
        const newNote = {
            title: $(".new-note-title").val(),
            content: $(".new-note-body").val(),
            status: "publish",
        };

        const baseUrl = universityData.root_url;
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
            },
            url: `${baseUrl}/wp-json/wp/v2/note`,
            type: "POST",
            data: newNote,
            success: (res) => {
                $(".new-note-title, .new-note-body").val("");
                $(`
                <li data-note-id="${res.id}">
                    <input readonly type="text" class="note-title-field" value="${res.title.raw}">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span> 
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                    <textarea readonly class="note-body-field" name="" id="">${res.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>
                    
                `)
                    .prependTo("#my-notes")
                    .hide()
                    .slideDown();
            },
            error: function (err) {
                if (err.responseText === "You have reached your note limit.") {
                    $(".note-limit-message").addClass("active");
                }
            },
        });
    }
}

// export default MyNotes;
