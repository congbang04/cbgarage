class BayService {
    constructor() {
        this.routes = window.routes || {};
    }

    /**
     * Get list of bays with filters
     */
    getBayList(params = {}, url = this.routes.bayList, callback) {
        $.ajax({
            url: url,
            method: "GET",
            data: params,
            dataType: "html",
            success: (response) => {
                if (callback) callback(response);
            },
            error: (xhr) => {
                console.error("Error fetching bay list:", xhr);
            },
        });
    }

    /**
     * Get bay detail from DOM data attributes
     */
    getBayDetail(bayId, callback) {
        const $bayCard = $(`.bay-card[data-bay-id="${bayId}"]`);
        if ($bayCard.length) {
            const bay = {
                id: bayId,
                name: $bayCard.data("bay-name"),
                branch_id: $bayCard.data("bay-branch"),
                type: $bayCard.data("bay-type"),
                status: $bayCard.data("bay-status"),
            };
            if (callback) callback({ success: true, bay: bay });
        } else {
            console.error("Bay not found in DOM");
            if (callback) callback({ success: false });
        }
    }

    /**
     * Create new bay
     */
    createBay(data, callback) {
        $.ajax({
            url: this.routes.bayStore || "/bays",
            method: "POST",
            data: JSON.stringify(data),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            success: (response) => {
                if (callback) callback(response);
            },
            error: (xhr) => {
                console.error("Error creating bay:", xhr);
                const response = xhr.responseJSON || {};
                if (callback) {
                    callback({
                        success: false,
                        errors: response.errors || { form: ["Đã xảy ra lỗi"] },
                    });
                }
            },
        });
    }

    /**
     * Update existing bay
     */
    updateBay(bayId, data, callback) {
        const url = (this.routes.bayUpdate || "/bays/:bay").replace(
            ":bay",
            bayId,
        );
        $.ajax({
            url: url,
            method: "PUT",
            data: JSON.stringify(data),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            success: (response) => {
                if (callback) callback(response);
            },
            error: (xhr) => {
                console.error("Error updating bay:", xhr);
                const response = xhr.responseJSON || {};
                if (callback) {
                    callback({
                        success: false,
                        errors: response.errors || { form: ["Đã xảy ra lỗi"] },
                    });
                }
            },
        });
    }

    /**
     * Delete bay
     */
    deleteBay(bayId, callback) {
        const url = (this.routes.bayDestroy || "/bays/:bay").replace(
            ":bay",
            bayId,
        );
        $.ajax({
            url: url,
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            success: (response) => {
                if (callback) callback(response);
            },
            error: (xhr) => {
                console.error("Error deleting bay:", xhr);
                if (callback) {
                    callback({
                        success: false,
                        message: "Đã xảy ra lỗi khi xóa cầu nâng",
                    });
                }
            },
        });
    }
}

export default BayService;
