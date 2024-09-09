// import React from "react";
import React, { useEffect, useState } from "react";

import { useSampleMeta } from "../api/getSampleMeta";
import {
    Box,
    Button,
    ButtonGroup,
    Center,
    HStack,
    Heading,
} from "@chakra-ui/react";
import { BaseInputField } from "@/components/Form/BaseInputField";
import { BaseDatePickerField } from "@/components/Form/BaseDatePickerField";
import { BaseDatePeriodField } from "@/components/Form/BaseDatePeriodField";
import { BaseRemoteMultiCheckboxField } from "@/components/Form/BaseRemoteMultiCheckboxField";
import { BaseRemoteSelectField } from "@/components/Form/BaseRemoteSelectField";

// 選択肢によって非表示とかのやつ
import { useWatch } from "react-hook-form";
import { useFormContext } from "react-hook-form";

import { SampleFormValuesType, SampleType } from "../types";

type FormPropType = {
    model: string;
    isConfirm?: boolean;
    isEdit?: boolean;
    
    locale?: string;
    data?: SampleType;
};

const Form = (props: FormPropType) => {
    const { model, data, isConfirm = false, isEdit = false , locale } = props;

    // ▼選択肢によって非表示とかのやつ
    const { setValue, getValues } = useFormContext<SampleFormValuesType>();
    const watchCategoryId = useWatch<SampleFormValuesType>({
        name: "master_area_id",
        defaultValue: isEdit ? data?.master_category_id : "",
    });
    const currentValues = getValues();
    useEffect(() => {;
        // 7a6f0c20-6f75-48f4-8f71-45557c6a5422だったら master_topic_categoriesを未選択状態に戻す
        if (watchCategoryId == '7a6f0c20-6f75-48f4-8f71-45557c6a5422') {
            setValue("master_topic_categories", []);
            // setValue("master_event_categories", ''); // 単数ならこっち
            const deleteButtons = document.querySelectorAll('.delete');
            deleteButtons.forEach(button => {
                (button as HTMLElement).click();
            });
        }
    });
    // ▲選択肢によって非表示とかのやつここまで

    const { data: meta } = useSampleMeta();


    return (
        <Box mb="2">
            <BaseInputField<SampleFormValuesType>
                id="title"
                formType="input"
                model={model}
                isConfirm={isConfirm}
                label="タイトル"
                placeholder="タイトルを入力してください"
                defaultValue=""
                formControlOptions={{ isRequired: true }}
                rule={{ required: "タイトルを入力してください" }}
            />
            <BaseDatePickerField<SampleFormValuesType>
                id="published"
                formType="date"
                model={model}
                isConfirm={isConfirm}
                label="公開日"
                placeholder="公開日を入力してください"
                defaultValue={new Date()
                    .toLocaleDateString()
                    .replaceAll("/", "-")}
                formControlOptions={{ isRequired: true }}
                rule={{ required: "公開日を入力してください" }}
            />
            <BaseDatePeriodField<SampleFormValuesType>
                id="start_date"
                formType="datePeriod"
                model={model}
                isConfirm={isConfirm}
                periodLabel="公開期間"
                periodConnector="~"
                defaultValue=""
                periodGroup={{
                    start: {
                        id: "start_date",
                        formType: "datetime",
                        defaultValue: "",
                    },
                    end: {
                        id: "end_date",
                        formType: "datetime",
                        defaultValue: "",
                    },
                }}
            />

            {meta && (
                <BaseRemoteSelectField<SampleFormValuesType>
                    id="master_area_id"
                    formType="remoteRadio"
                    label="エリア"
                    remoteDataKey="master_areas"
                    remoteDataIndexKey="title"
                    remoteDataValueKey="id"
                    placeholder="---"
                    meta={meta}
                    model={model}
                    isConfirm={isConfirm}
                    formControlOptions={{ isRequired: true }}
                    rule={{ required: "エリアを選択してください" }}
                />
            )}
            {/* {meta && (
                <BaseRemoteMultiCheckboxField<SampleFormValuesType>
                    id="master_areas"
                    formType="remoteMultiCheckbox"
                    label="エリア"
                    remoteDataKey="master_areas"
                    remoteDataIndexKey="title"
                    remoteDataValueKey="id"
                    meta={meta}
                    model={model}
                    isConfirm={isConfirm}
                    formControlOptions={{ isRequired: true }}
                    rule={{ required: "エリアを選択してください" }}
                    checkboxOptions={{ isRequired: false }}
                />
            )} */}

            {meta && (
                <BaseRemoteSelectField<SampleFormValuesType>
                    id="master_category_id"
                    formType="remoteRadio"
                    label="カテゴリ"
                    remoteDataKey="master_categories"
                    remoteDataIndexKey="title"
                    remoteDataValueKey="id"
                    placeholder="---"
                    meta={meta}
                    model={model}
                    isConfirm={isConfirm}
                    formControlOptions={{ isRequired: true }}
                    rule={{ required: "カテゴリを選択してください" }}
                />
            )}
            {meta && (
                // <BaseRemoteMultiCheckboxField<SampleFormValuesType>
                //     id="master_topic_categories"
                //     formType="remoteMultiCheckbox"
                //     label="カテゴリ"
                //     remoteDataKey="master_categories"
                //     remoteDataIndexKey="title"
                //     remoteDataValueKey="id"
                //     meta={meta}
                //     model={model}
                //     isConfirm={isConfirm}
                //     formControlOptions={{ isRequired: true }}
                //     rule={{ required: "カテゴリを選択してください" }}
                //     checkboxOptions={{ isRequired: false }}
                // />
                <BaseRemoteMultiCheckboxField<SampleFormValuesType>
                    id="master_topic_categories"
                    formType="remoteMultiCheckbox"
                    label="カテゴリ"
                    remoteDataKey="master_categories"
                    remoteDataIndexKey="title"
                    remoteDataValueKey="id"
                    meta={meta}
                    model={model}
                    isConfirm={isConfirm}
                    formControlOptions={{
                        isRequired: true,
                    }}
                    rule={{
                        required: "カテゴリを選択してください",
                    }}
                    useDefaultLocaleData={true}
                />
            )}
        </Box>
    );
};

export default Form;
