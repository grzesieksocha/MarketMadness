jQuery(document).ready(function() {
    var sharedTransactionsTable = $('.sharedTransactionsTable');
    var path = Routing.generate('checkSharedTransactions');


    setInterval(checkTransactions, 2000);
    checkTransactions();

    function checkTransactions() {
        $.ajax({
            type: 'GET',
            url: path,
            dataType: 'json',
            success: function (result) {
                console.log(result);
                sharedTransactionsTable.empty();
                sharedTransactionsTable.append("<tr><th>Date</th><th>Owner</th><th>Type</th><th>Stock</th><th>Price</th><th>Quantity</th></tr>");
                $.each (result, function (number, transaction) {
                    console.log(transaction.date);
                    var newRow = $("<tr></tr>");
                    newRow.append("<td>" + transaction.date + "</td>");
                    newRow.append("<td>" + transaction.user + "</td>");
                    newRow.append("<td>" + transaction.type + "</td>");
                    newRow.append("<td>" + transaction.symbol + "</td>");
                    newRow.append("<td>" + transaction.price + "</td>");
                    newRow.append("<td>" + transaction.quantity + "</td>");

                    sharedTransactionsTable.append(newRow);
                })
            },
            error: function () {
                console.log('ERROR')
            }
        });
    }
});
