# GetDiscount webservice

## Description

This services implments a discount engine. This implments a test exercice.

Customers, products and orders are defined as given in the test statement, they also have their own classes.

Regarding the definition of __discounts__, 2 files were used for its definitions: one JSON file `data/discounts.json`  the defines the discount id, scope and its rules (conditions that have to be met so that the discount may be applied) and another PHP file `DiscountActions.class.php` to define the actions that are executed to apply each discount.

The discount may be included in 1 of 3 scopes: Order, Category or Item.
The scopes define how we validate the rules and apply the discount.

## USAGE

To consume the web service there are 2 options: REST or SOAP.

### REST

To request a discount from an order simply pass the `orderJSON` GET or POST (both work) parameter with the JSON order definition as its value.

### SOAP

When using a SOAP request, you may find the WSDL definition under index.php?wsdl
The logic remains call getDiscount function and on the parameter orderJSON pass the order definition JSON string.

Since this implementation was done using NuSoap thrid party plugin it is not fully compatible and may give an error while using some SOAP clients.
