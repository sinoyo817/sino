import { AdminType } from "@/features/admins";
import { FileType } from "@/features/files";
import { MasterAreaType } from "@/features/master-areas";
import { MasterTopicCategoryType } from "@/features/master-topic-categories";

import {
    ApprovalRemandType,
    BaseBlockEntityType,
    BaseEntityType,
    CommonFilterParamType,
    IgnoreFormFieldsType,
    ResoponseCollectionType,
    MetadataType,
    BaseSelectOptions,
    
    MetaUtilityType,
} from "@/types";

/**
 * 追加していく
 */
export type SampleType = {
    title: string;
    published: string;
    start_date: string;
    end_date: string;
    file_id?: string;
    file?: FileType;
    file_alt?: string;
    summary?: string;
    summary_files?: string;
    
    create_admin?: AdminType;
    create_user?: AdminType;
    modified_admin?: AdminType;
    modified_user?: AdminType;
    approval_remands?: ApprovalRemandType[];
    sequence: number;
    
    master_area_id?: string;
    master_area?: MasterAreaType;
    master_areas?: MasterAreaType[];
     
    master_category_id?: string;
    master_topic_category?: MasterTopicCategoryType;
    master_topic_categories?: MasterTopicCategoryType[];
    class?: string; // 追加: カラムとしての class

    _translations?: Record<string, Omit<SampleType, "_translations">>;

} & BaseEntityType;

export type SampleListType = {
    data: SampleType[];
    collection: ResoponseCollectionType;
};

/**
 * 調整の必要があれば
 */
export type SampleFormValuesType = Omit<SampleType, IgnoreFormFieldsType>;

/**
 * 追加していく
 *
 * 例
 * {model? : string } & CommonFilterParamType
 *
 */
export type SampleFilterParamType = CommonFilterParamType;

/**
 * 追加していく
 *
 * 例
 * {master_category : BaseSelectOptions[] } & MetaUtilityType
 *
 */
export type SampleMetaType = {
    master_areas?: BaseSelectOptions[];
    master_categories?: BaseSelectOptions[];
} & MetaUtilityType;

