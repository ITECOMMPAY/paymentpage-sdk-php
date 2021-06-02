<?php

return [
    'empty_payment' => [
        '{
            "general":{
                "signature":"R5wq+3/y4lG9ty5sHR6MuscWBRg/bzAo9dxlMkVOUhQ+KpS7FwHMx+5saiLQnn90Rl7rhp1U37dgThSorpn1Ew==",
                "project_id":"[2] projectId"
            },
            "request":{
                "action":"[36] action",
                "errors":"[38] errors",
                "id":"[3] requestId",
                "status":"[37] status"
            },
            "customer":{
                "id":"[18] userLogin"
            },
            "token_status":"[71] status",
            "token_created_at":"[70] created_at",
            "token":"[68] tokenString"
        }',
        '{
            "general":{
                "signature":"+XNqUNPEo/mr1I3H/tGRCnNoWxqyqpzQIRIWv3eXfEzhfrqdZXHKHOWYlVryp1WayLTImLSqNUaDoqi3/G/y9w=="
            },
            "project_id":"[2] projectId",
            "recurring":{
                "id":"[100] id",
                "status":"[101] status",
                "type":"[102] type",
                "currency":"[107] currency",
                "exp_year":"[103] exp_year",
                "exp_month":"[104] exp_month",
                "period":"[105] period",
                "time":"[106] time"
            }
        }',
        '{
            "general":{
                "signature":"NpJuOiG6jSzh6CDNBYT9fqKZs9PkaXZQr3aqaN5yTvZdFj1sJaqWEhl1o4wCxyen2w0VhwdJ8p6ddplEqMWhUg==",
                "project_id":"[2] projectId"
            },
            "card":{
                "exp_month":"[73] month",
                "exp_year":"[72] year",
                "holder":"[74] holderName",
                "number":"[21] cardPanMasked"
            },
            "request":{
                "action":"[36] action",
                "errors":"[38] errors",
                "status":"[37] status",
                "id":"[3] requestId"
            },
            "customer":{
                "id":"[18] userLogin"
            },
            "token_status":"[71] status",
            "token_created_at":"[70] created_at",
            "token":"[68] tokenString"
        }',
        '{
            "general":{
                "project_id":"[2] projectId",
                "signature":"OIRodGzsjGGrrKpvH0hOE64zNKBrS7SvGbzxYSyhLmsoPCv2KuFXXQ6LXML7UnZWyLsvHfNV5TS0x4zmP0vXCw=="
            },
            "card":{
                "exp_month":"[73] month",
                "exp_year":"[72] year",
                "holder":"[74] holderName",
                "number":"[21] cardPanMasked",
                "country":"[86] country",
                "issuer_name":"[88] issuer_name"
            },
            "request":{
                "action":"[36] action",
                "errors":"[38] errors",
                "status":"[37] status",
                "id":"[3] requestId"
            },
            "customer":{
                "id":"[18] userLogin"
            },
            "token_status":"[71] status",
            "token_created_at":"[70] created_at",
            "token":"[68] tokenString"
        }',
    ],
    'isset_payment' => [
        '{
            "general":{
                "signature":"O5nJHj7yS4/6XC81VijFZFtEVhr6+OJ8Rb4FaRmHWR9Ti2H9L9jcvS8f9x2hQ5NgQQwlsEocrTb+YrDfnsSVHQ=="
            },
            "project_id":"[2] projectId",
            "payment":{
                "id":"[40] paymentId",
                "type":"[26] transactionType",
                "status":"[27] transactionStatus",
                "description":"[44] description",
                "date":"[30] internalProcessingDateTime",
                "method":"[17] paymentMethodTitle",
                "sum":"[23] transactionSum",
                "merchant_refund_id":"[451] merchantRefundId"
            },
            "account":{
                "token":"[41] token",
                "number":"[56] accountNumber"
            },
            "customer":{
                "id":"[18] userLogin",
                "phone":"[76] phone"
            },
            "clarification_fields":"[82] clarificationFields",
            "avs_result":"[83] avsResult",
            "provider_extra_fields":"[90] providerExtraFields",
            "recurring":{
                "id":"[100] id",
                "currency":"[107] currency",
                "valid_thru":"[108] valid_thru"
            },
            "operation":{
                "id":"[192] operation_id",
                "type":"[193] operation_type",
                "status":"[194] operation_status",
                "date":"[195] operation_date",
                "created_date":"[196] operation_created_date",
                "request_id":"[197] operation_request_id",
                "sum_initial":{
                    "amount":"[198] operation_sum_initial_amount",
                    "currency":"[199] operation_sum_initial_currency"
                },
                "sum_converted":{
                    "amount":"[200] operation_sum_converted_amount",
                    "currency":"[201] operation_sum_converted_currency"
                },
                "code":"[202] operation_code",
                "message":"[203] operation_message",
                "eci":"[205] operation_eci",
                "provider":{
                    "id":"[206] operation_provider_id",
                    "payment_id":"[207] operation_provider_payment_id",
                    "auth_code":"[210] operation_provider_auth_code",
                    "endpoint_id":"[211] operation_provider_endpoint_id",
                    "date":"[212] operation_provider_date"
                }
            }
        }',
        '{
            "general":{
                "signature":"Cm0L/+dLPg6Au/jEG0yr1FktzA1QwojRp95R9o5O4Wxx6MzjwTrso5B4rBXekM/fp5znut0WVGLFGG+TtJjuHQ=="
            },
            "redirectData":{
                "method":"GET",
                "body":[
                    
                ],
                "encrypted":[
                    
                ],
                "url":"url data"
            },
            "payment":{
                "id":"qwerty123"
            }
        }',
        '{
            "general":{
                "signature":"FZWW+aSwLzMDpS7A1IVNZ6uLYHphXIX8e9zdkHQB9IAqqWXKB5f/21vci2dv3h+kIB2kqTY2OKZCURs6v0th/w=="
            },
            "operation":{
                "sum_initial":{
                    "amount":"[198] operation_sum_initial_amount",
                    "currency":"[199] operation_sum_initial_currency"
                },
                "sum_converted":{
                    "amount":"[200] operation_sum_converted_amount",
                    "currency":"[201] operation_sum_converted_currency"
                },
                "eci":"[205] operation_eci",
                "provider":{
                    "id":"[206] operation_provider_id",
                    "payment_id":"[207] operation_provider_payment_id",
                    "auth_code":"[210] operation_provider_auth_code",
                    "endpoint_id":"[211] operation_provider_endpoint_id",
                    "date":"[212] operation_provider_date"
                },
                "id":"[192] operation_id",
                "type":"[193] operation_type",
                "status":"[194] operation_status",
                "date":"[195] operation_date",
                "created_date":"[196] operation_created_date",
                "request_id":"[197] operation_request_id"
            },
            "customer":{
                "phone":"[76] phone",
                "id":"[18] userLogin"
            },
            "recurring":{
                "id":"[100] id",
                "currency":"[107] currency",
                "valid_thru":"[108] valid_thru"
            },
            "provider_extra_fields":"[90] providerExtraFields",
            "clarification_fields":"[82] clarificationFields",
            "avs_result":"[83] avsResult",
            "account":{
                "token":"[41] token",
                "number":"[56] accountNumber"
            },
            "project_id":"[2] projectId",
            "payment":{
                "id":"[40] paymentId",
                "type":"[26] transactionType",
                "status":"[27] transactionStatus",
                "description":"[44] description",
                "date":"[30] internalProcessingDateTime",
                "method":"[17] paymentMethodTitle",
                "sum":"[23] transactionSum"
            },
            "errors":"[38] errors"
        }',
        '{
            "general":{
                "project_id":"[43] site_id",
                "payment_id":"[39] order_id",
                "signature":"q1aOeD+qH3L050pmRbw65p6vmUqMKT6f0hu1xGgMuh77Z9wDQvV7ySDH1y/mjFqy9Sc1qbnLDp2U83U8bWarIg=="
            },
            "threeds2":"[171] threeds2",
            "display_data":"[141] displayData",
            "additional_terminal_data":"[127] terminalAdditionalInfo",
            "payment":{
                "cascading_with_redirect":"[125] cascadingRedirect",
                "is_new_attempts_available":"[66] isNewAttemptsAvailable",
                "attempts_timeout":"[67] attemptsTimeout",
                "provider_id":"[45] id",
                "status":"[57] transactionStatusNonMapped",
                "id":"[40] paymentId",
                "method":"[17] paymentMethodTitle",
                "date":"[30] internalProcessingDateTime",
                "result_code":"[33] processorCode",
                "result_message":"[34] processorMessage",
                "split_with_redirect":"[381] splitWithRedirect"
            },
            "provider_extra_fields":"[90] providerExtraFields",
            "rrn":"[110] rrn",
            "AuthCode":"[31] authCode",
            "return_url":"[113] redirectData",
            "qr_code":"[114] qrCodeData",
            "acs":"[12] acsData",
            "clarification_fields":"[82] clarificationFields",
            "avs_result":"[83] avsResult",
            "account":{
                "number":"[56] accountNumber",
                "token":"[41] token"
            },
            "customer":{
                "id":"[18] userLogin"
            },
            "errors":"[38] errors",
            "request_id":"[3] requestId",
            "transaction":{
                "id":"[1] transactionId",
                "date":"[30] internalProcessingDateTime",
                "type":"[26] transactionType"
            },
            "description":"[44] description",
            "sum_request":{
                "amount":"[48] amount_incoming",
                "currency":"[49] currency_incoming"
            },
            "sum_real":{
                "amount":"[24] transactionAmountChannel",
                "currency":"[25] transactionCurrencyChannel"
            },
            "sum_refund":{
                "amount":"[46] transactionAmountRefund",
                "currency":"[47] transactionCurrencyRefund"
            }
        }',
    ],
];
